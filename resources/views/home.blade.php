@extends('layouts.app')

@section('title', 'Toolexa - Free Online Calculators, PDF, Image, Text & Developer Tools')
@section('description', 'Use free online calculators, PDF tools, image tools, text utilities, developer tools and seller label tools on Toolexa. Fast, private and mobile friendly.')
@section('keywords', 'free online tools, online calculators, pdf tools, image tools, text tools, developer tools, seller tools, gst calculator, emi calculator')

@section('content')
    <section class="home-hero">
        <div class="home-hero-content">
            <span class="eyebrow">Free online tools</span>
            <h1>Free Online Tools & Calculators</h1>
            <p>Toolexa gives you fast, clean and easy-to-use calculators, converters and utility tools for everyday work, finance planning and quick productivity tasks.</p>

            <form class="hero-search" method="GET" action="{{ route('search') }}">
                <label class="sr-only" for="home-search">Search for a tool</label>
                <input id="home-search" type="search" name="q" class="form-control" placeholder="Search for a tool...">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>

            <div class="quick-stats" aria-label="Toolexa quick stats">
                <span><strong>{{ count($tools) }}+</strong> Free Tools</span>
                <span><strong>Fast</strong> & Secure</span>
                <span><strong>No</strong> Registration Required</span>
            </div>

            <div class="home-quick-links" aria-label="Explore Toolexa collections">
                <a href="{{ route('category.show', 'pdf-tools') }}">PDF Tools</a>
                <a href="{{ route('category.show', 'image-tools') }}">Image Tools</a>
                <a href="{{ route('category.show', 'seller-tools') }}">Seller Tools</a>
                <a href="{{ route('blog.index') }}">Guides</a>
            </div>
        </div>
    </section>

    <section class="home-section" aria-labelledby="browse-category-heading">
        <div class="section-head">
            <div>
                <span class="eyebrow">Browse</span>
                <h2 id="browse-category-heading">Browse by Category</h2>
            </div>
        </div>

        <div class="category-grid">
            @foreach($homepageCategories as $category)
                @include('partials.category-card', ['category' => $category])
            @endforeach
        </div>
    </section>

    <section class="home-section" aria-labelledby="popular-tools-heading">
        <div class="section-head">
            <div>
                <span class="eyebrow">Most used</span>
                <h2 id="popular-tools-heading">Popular Tools</h2>
            </div>
        </div>

        <div class="tool-grid">
            @foreach($popular as $tool)
                @include('partials.home-tool-card', ['tool' => $tool, 'buttonLabel' => 'Open Tool'])
            @endforeach
        </div>
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'In content ad'])

    <section class="home-section" aria-labelledby="recent-tools-heading">
        <div class="section-head">
            <div>
                <span class="eyebrow">New</span>
                <h2 id="recent-tools-heading">Recently Added Tools</h2>
            </div>
            <a class="btn btn-sm" href="{{ route('search') }}">View All Tools</a>
        </div>

        <div class="recent-grid">
            @foreach($recentTools as $tool)
                <a class="recent-card" href="{{ route('tools.show', $tool['slug']) }}">
                    <strong>{{ $tool['name'] }}</strong>
                    <span>{{ $tool['category'] }}</span>
                    <p>{{ $tool['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    @if(!empty($latestArticles))
        <section class="home-section" aria-labelledby="latest-guides-heading">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Guides</span>
                    <h2 id="latest-guides-heading">Latest Blog Guides</h2>
                </div>
                <a class="btn btn-sm" href="{{ route('blog.index') }}">View Blog</a>
            </div>

            <div class="home-guide-grid">
                @foreach($latestArticles as $article)
                    <a class="home-guide-card" href="{{ route('blog.show', $article['slug']) }}">
                        <span>{{ $article['category'] }}</span>
                        <strong>{{ $article['title'] }}</strong>
                        <p>{{ $article['excerpt'] }}</p>
                        <small>{{ $article['reading_time'] }} min read</small>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="home-section" aria-labelledby="why-choose-heading">
        <div class="section-head">
            <div>
                <span class="eyebrow">Benefits</span>
                <h2 id="why-choose-heading">Why Choose Us</h2>
            </div>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-icon">FAST</span>
                <h3>Fast & Instant Results</h3>
                <p>All calculations are performed instantly.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">SAFE</span>
                <h3>Secure & Private</h3>
                <p>No personal data is stored.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">FREE</span>
                <h3>Completely Free</h3>
                <p>Use every tool without registration.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">MOB</span>
                <h3>Mobile Friendly</h3>
                <p>Works perfectly on desktop, tablet and mobile.</p>
            </article>
        </div>
    </section>

    <section class="seo-panel home-section" aria-labelledby="about-tools-heading">
        <span class="eyebrow">About</span>
        <h2 id="about-tools-heading">About Our Tools</h2>
        <p>
            We provide a growing collection of free online calculators, converters, developer tools and utility tools designed to help users complete everyday tasks quickly, accurately and securely. Our goal is to build a fast, clean and easy-to-use platform with useful tools for everyone.
        </p>
    </section>

    <section class="info-panel home-section faq-panel" aria-labelledby="home-faq-heading">
        <span class="eyebrow">FAQ</span>
        <h2 id="home-faq-heading">Frequently Asked Questions</h2>

        <details>
            <summary>Are all tools free?</summary>
            <p>Yes, every tool is completely free.</p>
        </details>
        <details>
            <summary>Do I need an account?</summary>
            <p>No registration is required.</p>
        </details>
        <details>
            <summary>Is my information stored?</summary>
            <p>No. Your data is processed securely and is not stored.</p>
        </details>
        <details>
            <summary>Can I use these tools on mobile?</summary>
            <p>Yes, all tools are fully responsive.</p>
        </details>
        <details>
            <summary>How often are new tools added?</summary>
            <p>New tools are added regularly.</p>
        </details>
    </section>

    <section class="newsletter-panel home-section" aria-labelledby="newsletter-heading">
        <div>
            <span class="eyebrow">Explore</span>
            <h2 id="newsletter-heading">Find the Right Tool Faster</h2>
            <p>Search tools, browse categories or read practical guides before using a calculator or converter.</p>
        </div>
        <div class="newsletter-actions">
            <a class="btn btn-primary" href="{{ route('search') }}">Find Tools</a>
            <a class="btn" href="{{ route('blog.index') }}">Read Guides</a>
        </div>
    </section>
@endsection
