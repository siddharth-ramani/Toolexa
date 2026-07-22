@extends('layouts.app')

@section('content')
    <article class="blog-article">
        <header class="tool-hero blog-article-hero">
            <span class="eyebrow">{{ $article['category'] }}</span>
            <h1>{{ $article['title'] }}</h1>
            <p>{{ $article['excerpt'] }}</p>
            <div class="blog-meta blog-meta-large">
                <span>{{ $article['author'] }}</span>
                <span>{{ \Carbon\Carbon::parse($article['published_at'])->format('F d, Y') }}</span>
                <span>{{ $article['reading_time'] }} min read</span>
            </div>
        </header>

        <figure class="blog-featured">
            @if($article['featured_image'])
                <img src="{{ $article['featured_image'] }}" alt="{{ $article['title'] }}" width="1200" height="675" loading="eager" fetchpriority="high" decoding="async">
            @else
                <div>
                    <span>{{ $article['category'] }}</span>
                    <strong>{{ $article['title'] }}</strong>
                </div>
            @endif
        </figure>

        <div class="blog-share-row">
            <a class="btn btn-sm" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonicalUrl) }}" rel="noopener" target="_blank">Facebook</a>
            <a class="btn btn-sm" href="https://twitter.com/intent/tweet?url={{ urlencode($canonicalUrl) }}&text={{ urlencode($article['title']) }}" rel="noopener" target="_blank">X</a>
            <a class="btn btn-sm" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($canonicalUrl) }}" rel="noopener" target="_blank">LinkedIn</a>
            <button class="btn btn-sm" type="button" data-copy-text="{{ $canonicalUrl }}">Copy Link</button>
        </div>

        <aside class="blog-toc info-panel" aria-label="Table of contents">
            <span class="eyebrow">Table of Contents</span>
            <ol>
                @foreach($toc as $item)
                    <li><a href="#{{ $item['id'] }}">{{ $item['title'] }}</a></li>
                @endforeach
                <li><a href="#faq">FAQs</a></li>
                <li><a href="#conclusion">Conclusion</a></li>
            </ol>
        </aside>

        <section class="blog-content info-panel">
            @foreach($article['sections'] as $section)
                <section id="{{ \Illuminate\Support\Str::slug($section['heading']) }}" class="blog-section">
                    <h2>{{ $section['heading'] }}</h2>
                    @foreach($section['paragraphs'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                    @if($loop->iteration === 2)
                        <h3>Practical example</h3>
                        <p>Open the related Toolexa tool, enter one realistic value, then change only one input at a time. This makes the effect of rate, format, size, quantity or setting easier to understand than changing everything together.</p>
                    @elseif($loop->iteration === 4)
                        <h3>Common mistake to avoid</h3>
                        <p>Do not rely on a result without checking the input type, unit, format or assumption behind it. Most wrong outputs come from entering the right number in the wrong field or using a setting that does not match the real task.</p>
                    @endif
                </section>
            @endforeach

            <section id="conclusion" class="blog-section">
                <h2>Conclusion</h2>
                <p>{{ $article['title'] }} becomes easier when you break the topic into clear inputs, practical examples and repeatable checks. Use this guide as a reference, then use the related Toolexa tools below whenever you need quick calculations, conversions or output you can copy.</p>
            </section>
        </section>

        <x-related-tools :tools="$relatedTools" heading="Try these Toolexa tools" />

        <section class="info-panel blog-author-box">
            <span class="eyebrow">Author</span>
            <h2>{{ $article['author'] }}</h2>
            <p>Toolexa Editorial Team creates practical guides for calculators, converters and browser-based productivity tools. Each article is written to help readers understand the concept, test real examples and use the related Toolexa tools with more confidence.</p>
        </section>

        <section id="faq" class="info-panel faq-panel">
            <span class="eyebrow">FAQs</span>
            <h2>{{ $article['title'] }} FAQs</h2>
            @foreach($article['faqs'] as $faq)
                <details>
                    <summary>{{ $faq['question'] }}</summary>
                    <p>{{ $faq['answer'] }}</p>
                </details>
            @endforeach
        </section>

        <x-related-articles :articles="$relatedArticles" heading="Keep Reading" />

        <nav class="blog-adjacent" aria-label="Previous and next articles">
            @if($previousArticle)
                <a class="blog-adjacent-card blog-adjacent-prev" href="{{ route('blog.show', $previousArticle['slug']) }}">
                    <span class="blog-adjacent-label">Previous Article</span>
                    <strong>{{ $previousArticle['title'] }}</strong>
                    <small>{{ $previousArticle['category'] }} · {{ $previousArticle['reading_time'] }} min read</small>
                </a>
            @endif

            @if($nextArticle)
                <a class="blog-adjacent-card blog-adjacent-next" href="{{ route('blog.show', $nextArticle['slug']) }}">
                    <span class="blog-adjacent-label">Next Article</span>
                    <strong>{{ $nextArticle['title'] }}</strong>
                    <small>{{ $nextArticle['category'] }} · {{ $nextArticle['reading_time'] }} min read</small>
                </a>
            @endif
        </nav>
    </article>
@endsection
