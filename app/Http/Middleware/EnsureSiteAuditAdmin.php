<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteAuditAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedUser = (string) config('site_audit.admin.username');
        $expectedPassword = (string) config('site_audit.admin.password');
        $user = (string) $request->getUser();
        $password = (string) $request->getPassword();

        if ($expectedUser === '' || $expectedPassword === ''
            || ! hash_equals($expectedUser, $user)
            || ! hash_equals($expectedPassword, $password)) {
            return response('Authentication required.', 401, [
                'WWW-Authenticate' => 'Basic realm="Toolexa Site Audit", charset="UTF-8"',
                'Cache-Control' => 'no-store, private',
                'X-Robots-Tag' => 'noindex, nofollow',
            ]);
        }

        return $next($request);
    }
}
