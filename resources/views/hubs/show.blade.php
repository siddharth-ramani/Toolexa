@extends('layouts.app')

@section('content')
    <article class="topic-hub-page">
        <header class="tool-hero topic-hub-hero">
            <span class="eyebrow">Toolexa topic hub</span>
            <h1>{{ $topic['title'] }}</h1>
            <p>{{ $topic['description'] }}</p>
            <div class="topic-hub-stats">
                <a href="#all-topic-tools"><strong>{{ $topic['tool_count'] }}</strong><span>Tools</span></a>
                <a href="#topic-articles"><strong>{{ $topic['article_count'] }}</strong><span>Articles</span></a>
                <a href="#topic-comparisons"><strong>{{ $topic['comparison_count'] }}</strong><span>Comparisons</span></a>
            </div>
        </header>

        <section class="info-panel topic-guide" aria-labelledby="beginner-guide-heading">
            <span class="eyebrow">Start here</span>
            <h2 id="beginner-guide-heading">Beginner's Guide to {{ $topic['title'] }}</h2>
            @foreach($topic['guide'] as $section)
                <section>
                    <h3>{{ $section['heading'] }}</h3>
                    @foreach($section['paragraphs'] as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                </section>
            @endforeach
        </section>

        @if(count($topic['featured_tools']))
            <section class="info-panel" aria-labelledby="featured-topic-tools-heading">
                <span class="eyebrow">Recommended starting points</span>
                <h2 id="featured-topic-tools-heading">Featured {{ $topic['title'] }}</h2>
                <div class="category-featured-grid">
                    @foreach($topic['featured_tools'] as $tool)<x-category-tool-card :tool="$tool" />@endforeach
                </div>
            </section>
        @endif

        <section id="all-topic-tools" class="info-panel" aria-labelledby="all-topic-tools-heading">
            <span class="eyebrow">Complete directory</span>
            <h2 id="all-topic-tools-heading">All {{ $topic['title'] }}</h2>
            <div class="category-tool-grid hub-tool-grid">
                @foreach($tools as $tool)<x-category-tool-card :tool="$tool" />@endforeach
            </div>
            {{ $tools->links('partials.pagination') }}
        </section>

        <section id="topic-comparisons" class="info-panel" aria-labelledby="topic-comparisons-heading">
            <span class="eyebrow">Understand the differences</span>
            <h2 id="topic-comparisons-heading">{{ $topic['title'] }} Comparisons</h2>
            @if(count($topic['comparisons']))
                <div class="hub-comparison-grid">
                    @foreach($topic['comparisons'] as $comparison)
                        <a href="{{ route('compare.show', $comparison['slug']) }}">
                            <span><b>{{ $comparison['left']['name'] }}</b><i>VS</i><b>{{ $comparison['right']['name'] }}</b></span>
                            <strong>{{ $comparison['title'] }}</strong>
                            <p>{{ $comparison['meta_description'] }}</p>
                            <small>Read comparison →</small>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="hub-section-empty">New comparisons for this topic will appear here automatically.</p>
            @endif
        </section>

        <section id="topic-articles" class="info-panel" aria-labelledby="topic-articles-heading">
            <span class="eyebrow">Continue learning</span>
            <h2 id="topic-articles-heading">Popular {{ $topic['title'] }} Articles</h2>
            @if(count($topic['articles']))
                <div class="premium-article-grid">
                    @foreach(array_slice($topic['articles'], 0, 6) as $article)<x-home-article-card :article="$article" />@endforeach
                </div>
            @else
                <p class="hub-section-empty">Relevant guides will appear here as they are published.</p>
            @endif
        </section>

        <section class="hub-reference-grid" aria-label="Practical topic reference">
            <article class="info-panel">
                <span class="eyebrow">Do this</span>
                <h2>Best Practices</h2>
                <ul>@foreach($topic['best_practices'] as $practice)<li>{{ $practice }}</li>@endforeach</ul>
            </article>
            <article class="info-panel">
                <span class="eyebrow">Avoid this</span>
                <h2>Common Mistakes</h2>
                <ul>@foreach($topic['mistakes'] as $mistake)<li>{{ $mistake }}</li>@endforeach</ul>
            </article>
        </section>

        <section class="info-panel hub-glossary" aria-labelledby="topic-glossary-heading">
            <span class="eyebrow">Essential terminology</span>
            <h2 id="topic-glossary-heading">{{ $topic['title'] }} Glossary</h2>
            <dl>
                @foreach($topic['glossary'] as $term => $definition)
                    <div><dt>{{ $term }}</dt><dd>{{ $definition }}</dd></div>
                @endforeach
            </dl>
        </section>

        <section class="info-panel faq-panel" aria-labelledby="topic-faq-heading">
            <span class="eyebrow">Questions answered</span>
            <h2 id="topic-faq-heading">{{ $topic['title'] }} FAQs</h2>
            @foreach($topic['faqs'] as $faq)<details><summary>{{ $faq['question'] }}</summary><p>{{ $faq['answer'] }}</p></details>@endforeach
        </section>

        <section class="info-panel" aria-labelledby="related-hubs-heading">
            <span class="eyebrow">Explore nearby topics</span>
            <h2 id="related-hubs-heading">Related Topic Hubs</h2>
            <div class="hub-related-grid">
                @foreach($topic['related_hubs'] as $hub)
                    <a href="{{ route('hub.show', $hub['slug']) }}"><strong>{{ $hub['title'] }}</strong><p>{{ $hub['description'] }}</p><small>Open topic hub →</small></a>
                @endforeach
            </div>
        </section>
    </article>
@endsection
