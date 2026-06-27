<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="@yield('keywords', $seoKeywords ?? 'online tools, gst calculator, emi calculator, age calculator, free calculators')">
    <meta name="description" content="@yield('description', $seoDescription ?? 'Free online calculators and utility tools for daily use.')">
    <meta name="theme-color" content="#0f766e">
    @if(config('services.google.site_verification'))
        <meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
    @endif

    <title>@yield('title', $seoTitle ?? 'Toolexa - Free Online Tools')</title>

    @php
        $basePath = rtrim(request()->getBaseUrl(), '/');
        $assetRoot = ($basePath === '' || str_ends_with($basePath, '/public'))
            ? $basePath.'/assets'
            : $basePath.'/public/assets';
        $manifestUrl = ($basePath === '' || str_ends_with($basePath, '/public'))
            ? $basePath.'/site.webmanifest'
            : $basePath.'/public/site.webmanifest';
        $faviconUrl = file_exists(public_path('assets/images/favicon.png'))
            ? $assetRoot.'/images/favicon.png'
            : (($basePath === '' || str_ends_with($basePath, '/public'))
                ? $basePath.'/favicon.ico'
                : $basePath.'/public/favicon.ico');
        $brandName = config('app.name', 'Toolexa');
        $logoUrl = null;
        foreach (['logo.svg', 'logo.png', 'logo.webp'] as $logoFile) {
            if (file_exists(public_path('assets/images/'.$logoFile))) {
                $logoUrl = $assetRoot.'/images/'.$logoFile;
                break;
            }
        }
        $categoryLinks = \App\Http\Controllers\Tools\HomeController::categories();
        $sidebarPopularTools = $popularTools ?? \App\Http\Controllers\Tools\HomeController::toolsBySlugs(
            \App\Http\Controllers\Tools\HomeController::popularSlugs()
        );
        $financeNavTools = \App\Http\Controllers\Tools\HomeController::toolsBySlugs([
            'gst-calculator',
            'emi-calculator',
            'sip-calculator',
            'fd-calculator',
            'ppf-calculator',
            'compound-interest-calculator',
            'loan-calculator',
            'cagr-calculator',
        ]);
        $utilityNavTools = collect(\App\Http\Controllers\Tools\HomeController::tools())
            ->filter(fn ($tool) => in_array($tool['category'], ['Utility', 'Text', 'Math', 'Shopping'], true))
            ->take(8)
            ->values();
    @endphp

    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link rel="manifest" href="{{ $manifestUrl }}">
    <link rel="stylesheet" href="{{ $assetRoot }}/css/bootstrap-lite.css">
    <link rel="stylesheet" href="{{ $assetRoot }}/css/style.css">

    @if(config('services.google.analytics_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.google.analytics_id') }}');
        </script>
    @endif

    @isset($schemaJsonLd)
        <script type="application/ld+json">@json($schemaJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
    @endisset
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>

    <nav class="site-nav" aria-label="Primary navigation">
        <div class="container nav-wrap">
            <a class="brand" href="{{ url('/') }}" aria-label="{{ $brandName }} home">
                @if($logoUrl)
                    <img class="brand-logo" src="{{ $logoUrl }}" alt="{{ $brandName }} logo" width="160" height="48">
                @else
                    <span class="brand-mark">TX</span>
                    <span>{{ $brandName }}</span>
                @endif
            </a>

            <button class="nav-toggle" type="button" data-nav-toggle aria-controls="siteMenu" aria-expanded="false" aria-label="Open navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="nav-menu" id="siteMenu">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>

                <div class="nav-dropdown">
                    <button class="nav-link dropdown-button {{ request()->is('tools/*') ? 'active' : '' }}" type="button" data-dropdown-toggle aria-expanded="false">
                        Finance Tools
                    </button>
                    <div class="dropdown-panel dropdown-panel-wide">
                        <span class="dropdown-title">Popular finance calculators</span>
                        <div class="dropdown-grid">
                            @foreach($financeNavTools as $tool)
                                <a href="{{ url('tools/'.$tool['slug']) }}">
                                    <strong>{{ $tool['name'] }}</strong>
                                    <small>{{ $tool['desc'] }}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="nav-dropdown">
                    <button class="nav-link dropdown-button" type="button" data-dropdown-toggle aria-expanded="false">
                        More Tools
                    </button>
                    <div class="dropdown-panel dropdown-panel-wide">
                        <span class="dropdown-title">Utility, text and everyday tools</span>
                        <div class="dropdown-grid">
                            @foreach($utilityNavTools as $tool)
                                <a href="{{ url('tools/'.$tool['slug']) }}">
                                    <strong>{{ $tool['name'] }}</strong>
                                    <small>{{ $tool['category'] }}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="nav-dropdown">
                    <button class="nav-link dropdown-button {{ request()->is('category/*') ? 'active' : '' }}" type="button" data-dropdown-toggle aria-expanded="false">
                        Categories
                    </button>
                    <div class="dropdown-panel">
                        @foreach($categoryLinks as $category)
                            <a href="{{ route('category.show', $category['slug']) }}">{{ $category['name'] }} Tools</a>
                        @endforeach
                    </div>
                </div>

                <div class="nav-dropdown">
                    <button class="nav-link dropdown-button {{ request()->is('about', 'contact', 'privacy-policy', 'terms', 'disclaimer') ? 'active' : '' }}" type="button" data-dropdown-toggle aria-expanded="false">
                        Company
                    </button>
                    <div class="dropdown-panel">
                        <a href="{{ route('page.show', 'about') }}">About</a>
                        <a href="{{ route('page.show', 'contact') }}">Contact</a>
                        <a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a>
                        <a href="{{ route('page.show', 'terms') }}">Terms</a>
                        <a href="{{ route('page.show', 'disclaimer') }}">Disclaimer</a>
                    </div>
                </div>

                <a class="nav-link nav-cta {{ request()->is('search') ? 'active' : '' }}" href="{{ route('search') }}">Find Tools</a>
            </div>
        </div>
    </nav>

    <main class="site-main" id="main-content">
        <div class="container">
            @include('partials.ad-slot', ['class' => 'ad-top', 'label' => 'Top responsive ad'])
        </div>

        <div class="container page-grid">
            <section class="content-area">
                @isset($breadcrumbs)
                    @include('partials.breadcrumb')
                @endisset

                @if ($errors->any())
                    <div class="alert-box">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')

                @isset($toolMeta)
                    @include('partials.tool-extras')
                @endisset
            </section>

            <aside class="sidebar-area" aria-label="Sponsored">
                @include('partials.ad-slot', ['class' => 'ad-sidebar', 'label' => 'Sidebar ad'])
                <div class="quick-box">
                    <span class="eyebrow">Popular</span>
                    @foreach($sidebarPopularTools as $tool)
                        <a href="{{ url('tools/'.$tool['slug']) }}">{{ $tool['name'] }}</a>
                    @endforeach
                </div>
            </aside>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a class="brand footer-brand" href="{{ url('/') }}">
                    @if($logoUrl)
                        <img class="brand-logo" src="{{ $logoUrl }}" alt="{{ $brandName }} logo" width="160" height="48" loading="lazy" decoding="async">
                    @else
                        <span class="brand-mark">TX</span>
                        <span>{{ $brandName }}</span>
                    @endif
                </a>
                <p>Free online calculators, converters and utility tools for everyday tasks.</p>
            </div>

            <nav aria-label="Footer tools">
                <h2>Tools</h2>
                <a href="{{ url('tools/gst-calculator') }}">GST Calculator</a>
                <a href="{{ url('tools/emi-calculator') }}">EMI Calculator</a>
                <a href="{{ url('tools/sip-calculator') }}">SIP Calculator</a>
                <a href="{{ url('tools/qr-generator') }}">QR Generator</a>
            </nav>

            <nav aria-label="Footer categories">
                <h2>Categories</h2>
                @foreach($categoryLinks as $category)
                    <a href="{{ route('category.show', $category['slug']) }}">{{ $category['name'] }}</a>
                @endforeach
            </nav>

            <nav aria-label="Footer company">
                <h2>Company</h2>
                <a href="{{ route('page.show', 'about') }}">About</a>
                <a href="{{ route('page.show', 'contact') }}">Contact</a>
                <a href="{{ route('search') }}">Search</a>
                <a href="{{ route('sitemap') }}">Sitemap</a>
            </nav>

            <nav aria-label="Footer legal">
                <h2>Legal</h2>
                <a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('page.show', 'terms') }}">Terms & Conditions</a>
                <a href="{{ route('page.show', 'disclaimer') }}">Disclaimer</a>
            </nav>
        </div>

        <div class="container footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $brandName }}. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="{{ $assetRoot }}/js/app.js"></script>
</body>
</html>
