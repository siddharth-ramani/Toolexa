@extends('layouts.app')

@section('content')
    <section class="tool-hero blog-hero">
        <span class="eyebrow">Toolexa Blog</span>
        <h1>Practical Guides for Online Tools</h1>
        <p>Clear explanations, examples and workflows for calculators, image tools, developer utilities and everyday productivity tasks.</p>
    </section>

    <section class="info-panel">
        <div class="section-head">
            <div>
                <span class="eyebrow">{{ $articles->total() }} articles</span>
                <h2>Latest Guides</h2>
            </div>
        </div>

        <div class="blog-grid">
            @foreach($articles as $article)
                <article class="card blog-card">
                    <a class="blog-card-media" href="{{ route('blog.show', $article['slug']) }}" aria-label="{{ $article['title'] }}">
                        @if($article['featured_image'])
                            <img src="{{ $article['featured_image'] }}" alt="{{ $article['title'] }}" width="720" height="405" loading="lazy" decoding="async">
                        @else
                            <span>{{ strtoupper(substr($article['category'], 0, 2)) }}</span>
                        @endif
                    </a>

                    <div class="blog-card-body">
                        <span class="eyebrow">{{ $article['category'] }}</span>
                        <h3><a href="{{ route('blog.show', $article['slug']) }}">{{ $article['title'] }}</a></h3>
                        <p>{{ $article['excerpt'] }}</p>
                        <div class="blog-meta">
                            <span>{{ $article['author'] }}</span>
                            <span>{{ \Carbon\Carbon::parse($article['published_at'])->format('M d, Y') }}</span>
                            <span>{{ $article['reading_time'] }} min read</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{ $articles->links('partials.pagination') }}
    </section>
@endsection
