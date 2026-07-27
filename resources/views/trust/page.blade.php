@extends('layouts.app')

@section('content')
    <div class="trust-page">
        <header class="tool-hero trust-hero">
            <span class="eyebrow">{{ $trustPage['eyebrow'] }}</span>
            <h1>{{ $trustPage['heading'] }}</h1>
            <p>{{ $trustPage['meta_description'] }}</p>
        </header>

        <section class="info-panel trust-introduction" aria-label="{{ $trustPage['name'] }} introduction">
            @foreach($trustPage['introduction'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </section>

        @if(!empty($trustPage['highlights']))
            <section class="trust-highlight-grid" aria-label="Trust commitments">
                @foreach($trustPage['highlights'] as $highlight)
                    <article class="card">
                        <span class="tool-icon" aria-hidden="true">{{ $highlight['icon'] }}</span>
                        <h2>{{ $highlight['title'] }}</h2>
                        <p>{{ $highlight['text'] }}</p>
                    </article>
                @endforeach
            </section>
        @endif

        <div class="trust-section-stack">
            @foreach($trustPage['sections'] as $section)
                <x-trust.section :section="$section" />
            @endforeach
        </div>

        @if(!empty($trustPage['faqs']))
            <x-trust.faqs :faqs="$trustPage['faqs']" />
        @endif

        <nav class="info-panel trust-policy-links" aria-label="Trust policies">
            <span class="eyebrow">More about our standards</span>
            <h2>Trust Center Policies</h2>
            <div>
                @foreach($trustPages as $slug => $policy)
                    @continue($slug === $trustSlug)
                    <a href="{{ route('trust.'.$slug) }}">
                        <strong>{{ $policy['name'] }}</strong>
                        <span>{{ $policy['meta_description'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        <footer class="trust-updated">
            <strong>{{ $trustPage['name'] }} Last Updated</strong>
            <time datetime="{{ config('trust.last_updated_iso') }}">{{ config('trust.last_updated') }}</time>
        </footer>
    </div>
@endsection
