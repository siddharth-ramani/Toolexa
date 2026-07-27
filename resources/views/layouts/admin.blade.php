<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Toolexa Internal Audit')</title>
    @php
        $basePath = rtrim(request()->getBaseUrl(), '/');
        $assetRoot = ($basePath === '' || str_ends_with($basePath, '/public')) ? $basePath.'/assets' : $basePath.'/public/assets';
        $assetVersion = substr(sha1((string) filemtime(public_path('assets/css/style.min.css')).'|'.(string) filemtime(public_path('assets/js/app.min.js'))), 0, 10);
    @endphp
    <link rel="stylesheet" href="{{ $assetRoot }}/css/bootstrap-lite.min.css?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ $assetRoot }}/css/style.min.css?v={{ $assetVersion }}">
</head>
<body class="audit-admin-body">
    <header class="audit-admin-nav">
        <div class="container">
            <a href="{{ route('admin.site-audit.index') }}">Toolexa Internal Quality</a>
            <nav aria-label="Internal quality tools">
                <a @class(['is-active' => request()->routeIs('admin.site-audit.*')]) href="{{ route('admin.site-audit.index') }}">Site Audit</a>
                <a @class(['is-active' => request()->routeIs('admin.adsense-validator.*')]) href="{{ route('admin.adsense-validator.index') }}">AdSense Readiness</a>
            </nav>
            <span>Private Admin Tool</span>
        </div>
    </header>
    <main class="container audit-admin-main">@yield('content')</main>
</body>
</html>
