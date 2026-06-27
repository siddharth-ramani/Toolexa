@extends('layouts.app')

@section('title', 'Free Online Tools & Calculators - Toolexa')
@section('description', 'Use free online tools and calculators for finance, text, utility, QR codes, passwords and everyday tasks. Fast, secure and mobile friendly.')

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
            <span class="eyebrow">Updates</span>
            <h2 id="newsletter-heading">Stay Updated</h2>
            <p>Get notified when new tools and features are added.</p>
        </div>
        <form class="newsletter-form" method="GET" action="{{ route('search') }}">
            <label class="sr-only" for="newsletter-email">Email address</label>
            <input id="newsletter-email" class="form-control" type="email" name="email" placeholder="Email address">
            <button class="btn btn-primary" type="submit">Subscribe</button>
        </form>
    </section>
@endsection
