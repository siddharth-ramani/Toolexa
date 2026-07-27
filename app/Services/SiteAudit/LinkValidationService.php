<?php

namespace App\Services\SiteAudit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LinkValidationService
{
    public function internal(string $url): array
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $base = rtrim(request()->getBaseUrl(), '/');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }
        if (str_starts_with($path, '/assets/') || str_starts_with($path, '/public/assets/')) {
            $relative = preg_replace('#^/(?:public/)?#', '', $path);
            return ['ok' => is_file(public_path($relative)), 'status' => is_file(public_path($relative)) ? 200 : 404];
        }
        if (in_array($path, ['/favicon.ico', '/site.webmanifest', '/robots.txt', '/ads.txt', '/sitemap.xml'], true)) {
            return ['ok' => true, 'status' => 200];
        }

        try {
            app('router')->getRoutes()->match(Request::create($path, 'GET'));
            return ['ok' => true, 'status' => 200];
        } catch (NotFoundHttpException) {
            return ['ok' => false, 'status' => 404];
        } catch (\Throwable) {
            return ['ok' => false, 'status' => 404];
        }
    }

    public function external(string $url): array
    {
        if (! config('site_audit.external_link_checks')) {
            return ['ok' => true, 'status' => null, 'skipped' => true];
        }

        return Cache::remember('site-audit:external:'.sha1($url), now()->addDay(), function () use ($url) {
            try {
                $response = Http::timeout((int) config('site_audit.external_link_timeout', 4))
                    ->withHeaders(['User-Agent' => 'Toolexa-Site-Audit/1.0'])
                    ->head($url);
                if (in_array($response->status(), [403, 405, 429], true)) {
                    $response = Http::timeout((int) config('site_audit.external_link_timeout', 4))
                        ->withHeaders(['User-Agent' => 'Toolexa-Site-Audit/1.0'])
                        ->get($url);
                }

                return ['ok' => $response->successful() || in_array($response->status(), [401, 403, 429], true), 'status' => $response->status()];
            } catch (\Throwable $exception) {
                return ['ok' => false, 'status' => null, 'error' => class_basename($exception)];
            }
        });
    }

    public function externalMany(array $urls): array
    {
        if (! config('site_audit.external_link_checks')) {
            return collect($urls)->mapWithKeys(fn (string $url) => [$url => ['ok' => true, 'status' => null, 'skipped' => true]])->all();
        }

        $results = [];
        $pending = [];
        foreach ($urls as $url) {
            $key = 'site-audit:external:'.sha1($url);
            if (Cache::has($key)) {
                $results[$url] = Cache::get($key);
            } else {
                $pending[sha1($url)] = $url;
            }
        }
        if ($pending === []) {
            return $results;
        }

        $responses = Http::pool(fn (Pool $pool) => collect($pending)->map(fn (string $url, string $hash) => $pool
            ->as($hash)
            ->timeout((int) config('site_audit.external_link_timeout', 4))
            ->withHeaders(['User-Agent' => 'Toolexa-Site-Audit/1.0'])
            ->head($url))->all());
        foreach ($pending as $hash => $url) {
            $response = $responses[$hash] ?? null;
            $result = $response instanceof HttpResponse
                ? ['ok' => $response->status() < 500 && ! in_array($response->status(), [404, 410], true), 'status' => $response->status()]
                : ['ok' => false, 'status' => null, 'error' => $response ? class_basename($response) : 'No response'];
            Cache::put('site-audit:external:'.sha1($url), $result, now()->addDay());
            $results[$url] = $result;
        }

        return $results;
    }
}
