@extends('layouts.app')

@section('title', 'Discover Fun Interactive Experiences | Toolexa')
@section('description', 'Create fun shareable experiences, collect anonymous responses, and enjoy interactive Discover features only on Toolexa.')
@section('keywords', 'toolexa discover, fun interactive experiences, anonymous responses, shareable links, viral social tools')

@section('content')
    <section class="tool-hero discover-landing-hero" data-ga-view="discover_landing_page_view">
        <span class="eyebrow">Discover</span>
        <h1>Discover</h1>
        <p>Interactive experiences designed to be shared with friends.</p>
    </section>

    <section class="home-section" aria-labelledby="discover-feature-heading">
        <div class="section-head">
            <div>
                <span class="eyebrow">Featured</span>
                <h2 id="discover-feature-heading">Start with something fun</h2>
            </div>
        </div>

        <div class="discover-feature-grid">
            @foreach($features as $card)
                <article class="discover-feature-card">
                    <div class="discover-feature-icon" aria-hidden="true">{{ $card->icon }}</div>
                    <div class="discover-feature-body">
                        <div class="discover-feature-meta">
                            <span>{{ $card->category }}</span>
                            @if($card->badge)
                                <strong>{{ $card->badge }}</strong>
                            @endif
                        </div>
                        <h3>{{ $card->name }}</h3>
                        <p>{{ $card->description }}</p>
                        <small>Launch: {{ \Carbon\CarbonImmutable::parse($card->launchDate)->format('M d, Y') }}</small>
                        <div class="discover-feature-actions">
                            @if($card->active)
                                <a class="btn btn-primary" href="{{ $card->url() }}" data-ga-event="feature_open" data-ga-label="{{ $card->slug }}">Start Now</a>
                                <a class="btn" href="{{ $card->demoUrl() }}" data-ga-event="demo_click" data-ga-label="{{ $card->slug }}">View Demo</a>
                            @else
                                <span class="btn disabled">Coming Soon</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="info-panel home-section text-center">
        <span class="eyebrow">Coming Soon</span>
        <h2>More exciting Discover experiences are coming soon.</h2>
        <p>First Impression, Rate Me, Guess My Age, Digital DNA, Personality Quiz, Aura Meter and Friendship Quiz are natural next steps for this section.</p>
    </section>
@endsection
