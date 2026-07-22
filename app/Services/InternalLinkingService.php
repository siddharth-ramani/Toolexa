<?php

namespace App\Services;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class InternalLinkingService
{
    private const CACHE_VERSION = 1;

    private const CACHE_TTL = 21600;

    private ?array $tools;

    private ?array $articles;

    public function __construct(?array $tools = null, ?array $articles = null)
    {
        $this->tools = $tools;
        $this->articles = $articles;
    }

    public function relatedToolsForTool(array $current, int $limit = 8): array
    {
        return $this->remember('tool-tools', $current['slug'], $limit, function () use ($current, $limit) {
            return $this->rank($current, $this->tools(), 'tool', 'tool', $limit);
        });
    }

    public function relatedArticlesForTool(array $current, int $limit = 4): array
    {
        return $this->remember('tool-articles', $current['slug'], $limit, function () use ($current, $limit) {
            return $this->rank($current, $this->articles(), 'tool', 'article', $limit);
        });
    }

    public function relatedToolsForArticle(array $current, int $limit = 6): array
    {
        return $this->remember('article-tools', $current['slug'], $limit, function () use ($current, $limit) {
            return $this->rank($current, $this->tools(), 'article', 'tool', $limit);
        });
    }

    public function relatedArticlesForArticle(array $current, int $limit = 3): array
    {
        return $this->remember('article-articles', $current['slug'], $limit, function () use ($current, $limit) {
            return $this->rank($current, $this->articles(), 'article', 'article', $limit);
        });
    }

    private function rank(array $current, array $candidates, string $sourceType, string $candidateType, int $limit): array
    {
        $sourceTerms = $this->terms($current, $sourceType);
        $popular = array_flip(HomeController::popularSlugs());
        $total = max(count($candidates), 1);

        return collect($candidates)
            ->reject(fn (array $candidate) => $sourceType === $candidateType && $candidate['slug'] === $current['slug'])
            ->unique('slug')
            ->map(function (array $candidate, int $index) use ($current, $sourceTerms, $sourceType, $candidateType, $popular, $total) {
                $candidateTerms = $this->terms($candidate, $candidateType);
                $shared = count(array_intersect($sourceTerms, $candidateTerms));
                $union = max(count(array_unique(array_merge($sourceTerms, $candidateTerms))), 1);
                $titleSimilarity = $this->similarity($this->title($current, $sourceType), $this->title($candidate, $candidateType));
                $slugSimilarity = $this->similarity($current['slug'], $candidate['slug']);
                $sameCategory = Str::lower($current['category'] ?? '') === Str::lower($candidate['category'] ?? '');
                $popularity = $candidateType === 'tool' && isset($popular[$candidate['slug']])
                    ? max(0, 8 - $popular[$candidate['slug']]) / 8
                    : 0;
                $recency = $candidateType === 'article'
                    ? $this->articleRecency($candidate)
                    : max(0, ($index + 1) / $total);

                $candidate['_internal_link_score'] = ($sameCategory ? 32 : 0)
                    + (($shared / $union) * 34)
                    + ($titleSimilarity * 14)
                    + ($slugSimilarity * 8)
                    + ($popularity * 7)
                    + ($recency * 5);

                return $candidate;
            })
            ->sortByDesc('_internal_link_score')
            ->take(max(0, $limit))
            ->map(function (array $candidate) {
                unset($candidate['_internal_link_score']);

                return $candidate;
            })
            ->values()
            ->all();
    }

    private function terms(array $item, string $type): array
    {
        $parts = $type === 'tool'
            ? [$item['name'] ?? '', $item['slug'] ?? '', $item['category'] ?? '', $item['keywords'] ?? '', $item['desc'] ?? '', $item['seo_description'] ?? '']
            : [$item['title'] ?? '', $item['slug'] ?? '', $item['category'] ?? '', $item['excerpt'] ?? '', $item['meta_description'] ?? ''];

        if (! empty($item['tags'])) {
            $parts[] = implode(' ', (array) $item['tags']);
        }

        return collect(preg_split('/[^\pL\pN]+/u', Str::lower(implode(' ', $parts))) ?: [])
            ->filter(fn (string $term) => mb_strlen($term) > 2 && ! in_array($term, $this->stopWords(), true))
            ->unique()
            ->values()
            ->all();
    }

    private function similarity(string $left, string $right): float
    {
        $left = str_replace('-', ' ', Str::lower($left));
        $right = str_replace('-', ' ', Str::lower($right));
        similar_text($left, $right, $percentage);

        return $percentage / 100;
    }

    private function title(array $item, string $type): string
    {
        return (string) ($type === 'tool' ? ($item['name'] ?? '') : ($item['title'] ?? ''));
    }

    private function articleRecency(array $article): float
    {
        $timestamp = strtotime($article['published_at'] ?? '') ?: 0;
        $ageInDays = max(0, (time() - $timestamp) / 86400);

        return 1 / (1 + ($ageInDays / 365));
    }

    private function remember(string $relation, string $slug, int $limit, callable $resolver): array
    {
        $key = implode(':', ['internal-links', self::CACHE_VERSION, $this->catalogFingerprint(), $relation, $slug, $limit]);

        return Cache::remember($key, now()->addSeconds(self::CACHE_TTL), $resolver);
    }

    private function catalogFingerprint(): string
    {
        $tools = collect($this->tools())->map(fn (array $tool) => $tool['slug'])->all();
        $articles = collect($this->articles())->map(fn (array $article) => $article['slug'].':'.($article['published_at'] ?? ''))->all();

        return substr(sha1(json_encode([$tools, $articles])), 0, 12);
    }

    private function tools(): array
    {
        return $this->tools ??= HomeController::tools();
    }

    private function articles(): array
    {
        return $this->articles ??= BlogRepository::all();
    }

    private function stopWords(): array
    {
        return ['and', 'the', 'for', 'with', 'from', 'this', 'that', 'your', 'you', 'tool', 'tools', 'online', 'free', 'toolexa', 'calculator', 'generator', 'guide', 'using', 'into', 'how'];
    }
}
