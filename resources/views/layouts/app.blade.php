<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $pageTitle = trim($__env->yieldContent('title', $seoTitle ?? 'Toolexa - Free Online Tools'));
        $pageDescription = trim($__env->yieldContent('description', $seoDescription ?? 'Free online calculators and utility tools for daily use.'));
        $pageKeywords = trim($__env->yieldContent('keywords', $seoKeywords ?? 'online tools, gst calculator, emi calculator, age calculator, free calculators'));
        $pageCanonical = $canonicalUrl ?? url()->current();
        $robotsMeta = $robotsMeta ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    @endphp
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robotsMeta }}">
    <meta name="theme-color" content="#0f766e">
    @if(config('services.google.site_verification'))
        <meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
    @endif

    <title>{{ $pageTitle }}</title>

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
            ->filter(fn ($tool) => in_array($tool['category'], ['Utility', 'Utility Tools', 'Text Tools', 'Developer Tools', 'SEO Tools', 'Security Tools', 'Date & Time Tools', 'Color Tools', 'Business Tools', 'Image Tools', 'PDF Tools', 'Seller Tools', 'Web Tools', 'Math', 'Shopping'], true))
            ->take(8)
            ->values();
        $footerGuides = class_exists(\App\Support\BlogRepository::class)
            ? collect(\App\Support\BlogRepository::all())->take(3)->values()
            : collect();
        $footerToolGroups = [
            'Calculators' => \App\Http\Controllers\Tools\HomeController::toolsBySlugs([
                'gst-calculator',
                'emi-calculator',
                'sip-calculator',
                'percentage-calculator',
                'compound-interest-calculator',
            ]),
            'Text & Developer' => \App\Http\Controllers\Tools\HomeController::toolsBySlugs([
                'word-counter',
                'text-case-converter',
                'json-formatter',
                'json-validator',
                'base64-encoder',
                'uuid-generator',
            ]),
            'Image & PDF' => \App\Http\Controllers\Tools\HomeController::toolsBySlugs([
                'image-compressor',
                'image-resizer',
                'image-to-pdf-converter',
                'pdf-merger',
            ]),
            'Seller Tools' => \App\Http\Controllers\Tools\HomeController::toolsBySlugs([
                'meesho-label-cropper',
                'amazon-label-cropper',
                'flipkart-label-cropper',
                'myntra-label-cropper',
                'ajio-label-cropper',
            ]),
        ];
        $socialImageUrl = $socialImageUrl ?? ($logoUrl ?: $faviconUrl);
        $globalSchemas = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $brandName,
                'url' => url('/'),
                'logo' => $socialImageUrl,
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $brandName,
                'url' => url('/'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('search').'?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ];
        $pageSchemas = $schemaJsonLd ?? [];
        $pageSchemas = $pageSchemas ? (array_is_list($pageSchemas) ? $pageSchemas : [$pageSchemas]) : [];
        $allSchemaJsonLd = array_merge($globalSchemas, $pageSchemas);
    @endphp

    <link rel="canonical" href="{{ $pageCanonical }}">
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link rel="manifest" href="{{ $manifestUrl }}">
    <meta property="og:site_name" content="{{ $brandName }}">
    <meta property="og:type" content="{{ request()->is('blog/*') ? 'article' : 'website' }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $pageCanonical }}">
    <meta property="og:image" content="{{ $socialImageUrl }}">
    @isset($article)
        <meta property="article:published_time" content="{{ $article['published_at'] }}">
        <meta property="article:modified_time" content="{{ $article['published_at'] }}">
        <meta property="article:author" content="{{ $article['author'] }}">
        <meta property="article:section" content="{{ $article['category'] }}">
    @endisset
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $socialImageUrl }}">
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

    <script type="application/ld+json">@json($allSchemaJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
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
                            @php($categoryLabel = str_contains($category['name'], 'Tools') ? $category['name'] : $category['name'].' Tools')
                            <a href="{{ route('category.show', $category['slug']) }}">{{ $categoryLabel }}</a>
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

                <a class="nav-link {{ request()->is('blog*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a>
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
        <div class="container footer-top">
            <div class="footer-about">
                <a class="brand footer-brand" href="{{ url('/') }}">
                    @if($logoUrl)
                        <img class="brand-logo" src="{{ $logoUrl }}" alt="{{ $brandName }} logo" width="160" height="48" loading="lazy" decoding="async">
                    @else
                        <span class="brand-mark">TX</span>
                        <span>{{ $brandName }}</span>
                    @endif
                </a>
                <p>Free online calculators, converters and utility tools for everyday tasks.</p>
                <div class="footer-actions">
                    <a href="{{ route('search') }}">Find Tools</a>
                    <a href="{{ route('blog.index') }}">Read Guides</a>
                </div>
            </div>

            <div class="footer-category-strip" aria-label="Footer categories">
                @foreach($categoryLinks as $category)
                    <a href="{{ route('category.show', $category['slug']) }}">{{ $category['name'] }}</a>
                @endforeach
            </div>
        </div>

        <div class="container footer-grid">
            @foreach($footerToolGroups as $groupName => $tools)
                <nav aria-label="Footer {{ $groupName }}">
                    <h2>{{ $groupName }}</h2>
                    @foreach($tools as $tool)
                        <a href="{{ url('tools/'.$tool['slug']) }}">{{ $tool['name'] }}</a>
                    @endforeach
                </nav>
            @endforeach

            @if($footerGuides->count())
                <nav aria-label="Footer guides">
                    <h2>Guides</h2>
                    @foreach($footerGuides as $guide)
                        <a href="{{ route('blog.show', $guide['slug']) }}">{{ $guide['title'] }}</a>
                    @endforeach
                    <a href="{{ route('blog.index') }}">All Guides</a>
                </nav>
            @endif

            <nav aria-label="Footer quick links">
                <h2>Quick Links</h2>
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ route('search') }}">Tools</a>
                <a href="{{ route('blog.index') }}">Blogs</a>
            </nav>

            <nav aria-label="Footer company">
                <h2>Company</h2>
                <a href="{{ route('page.show', 'about') }}">About</a>
                <a href="{{ route('page.show', 'contact') }}">Contact</a>
                <a href="{{ route('sitemap') }}">Sitemap</a>
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
