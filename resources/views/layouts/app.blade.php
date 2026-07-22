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
        $workspaceEmbed = request()->boolean('workspace') && request()->is('tools/*');
    @endphp
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robotsMeta }}">
    <meta name="theme-color" content="#0f766e">
    <meta name="workspace-url" content="{{ isset($toolMeta) ? route('workspace', ['add' => $toolMeta['slug']]) : route('workspace') }}">
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
        $modernLogoUrl = file_exists(public_path('assets/images/logo.webp')) ? $assetRoot.'/images/logo.webp' : null;
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
        $dashboardPageItem = null;
        if (isset($toolMeta)) {
            $dashboardPageItem = ['type' => 'tool', 'slug' => $toolMeta['slug'], 'title' => $toolMeta['name'], 'url' => url('tools/'.$toolMeta['slug']), 'icon' => $toolMeta['icon'], 'category' => $toolMeta['category'], 'description' => $toolMeta['desc']];
        } elseif (request()->is('blog/*') && isset($article)) {
            $dashboardPageItem = ['type' => 'article', 'slug' => $article['slug'], 'title' => $article['title'], 'url' => route('blog.show', $article['slug']), 'icon' => 'BLOG', 'category' => $article['category'], 'description' => $article['excerpt']];
        } elseif (isset($comparison)) {
            $dashboardPageItem = ['type' => 'comparison', 'slug' => $comparison['slug'], 'title' => $comparison['title'], 'url' => route('compare.show', $comparison['slug']), 'icon' => 'VS', 'category' => 'Comparison', 'description' => $comparison['meta_description']];
        }
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
    @if(request()->is('blog/*') && isset($article))
        <meta property="article:published_time" content="{{ $article['published_at'] }}">
        <meta property="article:modified_time" content="{{ $article['published_at'] }}">
        <meta property="article:author" content="{{ $article['author'] }}">
        <meta property="article:section" content="{{ $article['category'] }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $socialImageUrl }}">
    <link rel="stylesheet" href="{{ $assetRoot }}/css/bootstrap-lite.min.css">
    <link rel="stylesheet" href="{{ $assetRoot }}/css/style.min.css">

    @if(config('services.google.adsense_publisher_id'))
        <link rel="preconnect" href="https://pagead2.googlesyndication.com" crossorigin>
    @endif
    @if(config('services.google.analytics_id'))
        <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    @endif

    @if(config('services.google.adsense_publisher_id'))
        <script async
                src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.google.adsense_publisher_id') }}"
                crossorigin="anonymous"></script>
    @endif

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
<body class="{{ $workspaceEmbed ? 'workspace-embedded-page' : '' }}" @if($dashboardPageItem) data-dashboard-item="{{ json_encode($dashboardPageItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}" @endif>
    <a class="skip-link" href="#main-content">Skip to content</a>

    @unless($workspaceEmbed)
    <nav class="site-nav" aria-label="Primary navigation">
        <div class="container nav-wrap">
            <a class="brand" href="{{ url('/') }}" aria-label="{{ $brandName }} home">
                @if($logoUrl)
                    <picture>@if($modernLogoUrl)<source srcset="{{ $modernLogoUrl }}" type="image/webp">@endif<img class="brand-logo" src="{{ $logoUrl }}" alt="{{ $brandName }} logo" width="160" height="48" fetchpriority="high" decoding="async"></picture>
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
                <a class="nav-link {{ request()->is('workspace') ? 'active' : '' }}" href="{{ route('workspace') }}">Workspace</a>
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">My Dashboard</a>
                <a class="nav-link nav-cta {{ request()->is('search') ? 'active' : '' }}" href="{{ route('search') }}">Find Tools</a>
            </div>
        </div>
    </nav>
    @endunless

    <main class="site-main" id="main-content">
        @unless($workspaceEmbed)
        <div class="container">
            @include('partials.ad-slot', ['class' => 'ad-top', 'label' => 'Top responsive ad'])
        </div>
        @endunless

        <div class="container page-grid {{ request()->is('/', 'dashboard', 'workspace') || $workspaceEmbed ? 'page-grid-home' : '' }}">
            <section class="content-area">
                @if(isset($breadcrumbs) && ! $workspaceEmbed)
                    @include('partials.breadcrumb')
                @endif

                @if ($errors->any())
                    <div class="alert-box">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')

                @if(isset($toolMeta) && ! $workspaceEmbed)
                    @include('partials.tool-extras')
                @endif
            </section>

            @unless(request()->is('/', 'dashboard', 'workspace') || $workspaceEmbed)
                <aside class="sidebar-area" aria-label="Sponsored">
                    @include('partials.ad-slot', ['class' => 'ad-sidebar', 'label' => 'Sidebar ad'])
                    <div class="quick-box">
                        <span class="eyebrow">Popular</span>
                        @foreach($sidebarPopularTools as $tool)
                            <a href="{{ url('tools/'.$tool['slug']) }}">{{ $tool['name'] }}</a>
                        @endforeach
                    </div>
                </aside>
            @endunless
        </div>
    </main>

    @unless($workspaceEmbed)
    <footer class="site-footer">
        <div class="container footer-top">
            <div class="footer-about">
                <a class="brand footer-brand" href="{{ url('/') }}">
                    @if($logoUrl)
                        <picture>@if($modernLogoUrl)<source srcset="{{ $modernLogoUrl }}" type="image/webp">@endif<img class="brand-logo" src="{{ $logoUrl }}" alt="{{ $brandName }} logo" width="160" height="48" loading="lazy" decoding="async"></picture>
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
                <a href="{{ route('compare.index') }}">Comparisons</a>
                <a href="{{ route('hub.index') }}">Topic Hubs</a>
                <a href="{{ route('workspace') }}">Workspace</a>
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
    @endunless

    @if($dashboardPageItem || request()->is('dashboard'))
        <script src="{{ $assetRoot }}/js/dashboard.min.js" defer></script>
    @endif
    @if(request()->is('workspace'))
        <script src="{{ $assetRoot }}/js/workspace.min.js" defer></script>
    @endif
    <script src="{{ $assetRoot }}/js/app.min.js" defer></script>
</body>
</html>
