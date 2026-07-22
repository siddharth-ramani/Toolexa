@extends('layouts.app')

@section('title', '100+ Free Online Tools & Calculators | Toolexa')
@section('description', 'Use 100+ free online calculators, PDF, image, text, developer, SEO and utility tools on Toolexa. Fast, private, mobile friendly and no signup required.')
@section('keywords', 'free online tools, online calculators, PDF tools, image tools, developer tools, SEO tools, finance calculators, Toolexa')

@section('content')
    <header class="home-hero premium-home-hero">
        <div class="home-hero-content">
            <span class="eyebrow">Fast · Private · No signup</span>
            <h1>100+ Free Online Tools &amp; Calculators</h1>
            <p>Calculate, convert and create in seconds with simple tools that work directly in your browser.</p>

            <form class="hero-search premium-hero-search" method="GET" action="{{ route('search') }}" role="search">
                <label class="sr-only" for="home-search">Search all Toolexa tools and articles</label>
                <span class="premium-search-icon" aria-hidden="true">⌕</span>
                <input id="home-search" type="search" name="q" class="form-control" placeholder="Search tools, calculators and guides..." autocomplete="off">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>

            <div class="home-popular-searches" aria-label="Popular searches">
                <span>Popular:</span>
                @foreach(array_slice($popularSearches, 0, 6) as $search)
                    <a href="{{ route('search', ['q' => $search]) }}">{{ $search }}</a>
                @endforeach
            </div>

            <div class="premium-hero-stats" aria-label="Toolexa statistics">
                <span><strong>{{ $toolCount }}+</strong> Tools</span>
                <span><strong>{{ $articleCount }}+</strong> Articles</span>
                <span><strong>100%</strong> Free</span>
                <span><strong>No</strong> Signup</span>
            </div>
        </div>
    </header>

    <section id="featured-categories" class="home-section" aria-labelledby="featured-categories-heading">
        <x-home-section-heading eyebrow="Explore" title="Featured Categories" id="featured-categories-heading" description="Start with a collection built around the task you need to complete." />
        <div class="premium-category-grid">
            @foreach($featuredCategories as $category)
                <x-home-category-card :category="$category" />
            @endforeach
        </div>
    </section>

    <section class="home-section home-lazy-section" aria-labelledby="trending-tools-heading">
        <x-home-section-heading eyebrow="Most popular" title="Trending Tools" id="trending-tools-heading" description="Frequently opened tools for calculations, conversions and everyday tasks." :url="route('search')" link-label="View All" />
        <div class="tool-grid premium-tool-grid">
            @foreach($trendingTools as $tool)
                @include('partials.home-tool-card', ['tool' => $tool, 'buttonLabel' => 'Open Tool'])
            @endforeach
        </div>
    </section>

    <section class="home-section home-lazy-section" aria-labelledby="recent-tools-heading">
        <x-home-section-heading eyebrow="New on Toolexa" title="Recently Added" id="recent-tools-heading" description="The latest additions to our growing browser-based toolkit." :url="route('search')" link-label="Browse Tools" />
        <div class="tool-grid premium-tool-grid">
            @foreach($recentTools as $tool)
                @include('partials.home-tool-card', ['tool' => $tool, 'buttonLabel' => 'Try New Tool'])
            @endforeach
        </div>
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'In content ad'])

    <section class="home-section home-lazy-section" aria-labelledby="featured-articles-heading">
        <x-home-section-heading eyebrow="Learn" title="Featured Articles" id="featured-articles-heading" description="Practical guides that help you understand the task before using a tool." :url="route('blog.index')" link-label="All Articles" />
        <div class="premium-article-grid">
            @foreach($featuredArticles as $article)
                <x-home-article-card :article="$article" />
            @endforeach
        </div>
    </section>

    <section class="home-section editor-picks-panel home-lazy-section" aria-labelledby="editor-picks-heading">
        <x-home-section-heading eyebrow="Curated" title="Editor's Picks" id="editor-picks-heading" description="Useful tools selected by the Toolexa editorial team." />
        <div class="mini-tool-grid premium-picks-grid">
            @foreach($editorPicks as $tool)
                @include('partials.tool-card', ['tool' => $tool])
            @endforeach
        </div>
    </section>

    <section class="home-section home-lazy-section" aria-labelledby="why-choose-heading">
        <x-home-section-heading eyebrow="Built for everyone" title="Why Choose Toolexa?" id="why-choose-heading" description="Simple tools, clear results and fewer barriers between you and the task." />
        <div class="premium-benefit-grid">
            @foreach([
                ['FREE', 'Free Forever', 'Use the complete toolkit without subscriptions.'],
                ['OPEN', 'No Registration', 'Start immediately without creating an account.'],
                ['PRIV', 'Privacy First', 'Browser-first processing whenever the tool supports it.'],
                ['WEB', 'Works in Browser', 'No specialist desktop software to install.'],
                ['MOB', 'Mobile Friendly', 'Comfortable layouts across phones, tablets and desktops.'],
                ['FAST', 'Fast Processing', 'Focused interfaces designed for quick results.'],
                ['SAFE', 'Secure', 'Clear guidance and privacy-conscious workflows.'],
                ['LOCAL', 'No Upload Required', 'Compatible tools keep your inputs on your device.'],
            ] as [$icon, $title, $description])
                <article><span class="feature-icon">{{ $icon }}</span><h3>{{ $title }}</h3><p>{{ $description }}</p></article>
            @endforeach
        </div>
    </section>

    <section class="home-statistics home-section home-lazy-section" aria-labelledby="website-statistics-heading">
        <div>
            <span class="eyebrow">Growing every week</span>
            <h2 id="website-statistics-heading">A toolkit built for real work</h2>
            <p>Every new tool and guide expands a connected library designed to help users move from question to result.</p>
        </div>
        <div class="home-counter-grid">
            <article><strong data-counter="{{ $toolCount }}" data-counter-suffix="+">0</strong><span>Tools</span></article>
            <article><strong data-counter="{{ $articleCount }}" data-counter-suffix="+">0</strong><span>Articles</span></article>
            <article><strong data-counter="1000" data-counter-suffix="s+">0</strong><span>Calculations</span></article>
            <article><strong data-counter="{{ $categoryCount }}">0</strong><span>Categories</span></article>
        </div>
    </section>

    <section class="home-section home-lazy-section" aria-labelledby="popular-collections-heading">
        <x-home-section-heading eyebrow="Handy shortcuts" title="Popular Collections" id="popular-collections-heading" description="Jump into a focused group of tools for a common workflow." />
        <div class="home-collection-grid">
            @foreach($collections as $collection)
                <a href="{{ route('category.show', $collection['category']) }}">
                    <span class="tool-icon">{{ $collection['icon'] }}</span>
                    <strong>{{ $collection['title'] }}</strong>
                    <p>{{ $collection['description'] }}</p>
                    <small>{{ $collection['count'] }} tools · Explore collection →</small>
                </a>
            @endforeach
        </div>
    </section>

    <section class="info-panel home-section faq-panel home-lazy-section" aria-labelledby="home-faq-heading">
        <span class="eyebrow">Questions answered</span>
        <h2 id="home-faq-heading">Frequently Asked Questions</h2>
        @foreach($faqs as $faq)
            <details>
                <summary>{{ $faq['question'] }}</summary>
                <p>{{ $faq['answer'] }}</p>
            </details>
        @endforeach
    </section>

    <section class="newsletter-panel premium-newsletter home-section home-lazy-section" aria-labelledby="newsletter-heading">
        <div>
            <span class="eyebrow">Stay updated</span>
            <h2 id="newsletter-heading">Get notified when new free tools are added.</h2>
            <p>Occasional product updates. No noise, and no account required.</p>
        </div>
        <form class="newsletter-form" data-newsletter-form>
            <label class="sr-only" for="newsletter-email">Email address</label>
            <input id="newsletter-email" class="form-control" type="email" placeholder="you@example.com" required>
            <button class="btn btn-primary" type="submit">Notify Me</button>
            <small data-newsletter-status aria-live="polite">Newsletter delivery will be connected soon.</small>
        </form>
    </section>

    <section class="home-final-cta home-section home-lazy-section" aria-labelledby="final-cta-heading">
        <span class="eyebrow">Start now</span>
        <h2 id="final-cta-heading">Find the right tool in seconds.</h2>
        <p>Search calculators, utilities, categories and practical guides from one place.</p>
        <form class="hero-search" method="GET" action="{{ route('search') }}" role="search">
            <label class="sr-only" for="footer-tool-search">Search Toolexa</label>
            <input id="footer-tool-search" class="form-control" type="search" name="q" placeholder="Search 100+ free tools...">
            <button class="btn btn-primary" type="submit">Search Toolexa</button>
        </form>
    </section>
@endsection
