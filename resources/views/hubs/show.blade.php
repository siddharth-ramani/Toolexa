@extends('layouts.app')

@section('content')
    <article class="topic-hub-page">
        <x-authority.hero :title="$topic['title']" :description="$topic['description']" :authority="$topic['authority']" :editorial-meta="$editorialMeta" />
        <x-authority.overview :title="$topic['title']" :paragraphs="$topic['authority']['overview']" />
        <x-authority.featured-tools :title="$topic['title']" :selections="$topic['featured_selections']" />
        <x-authority.tool-directory :title="$topic['title']" :tools="$tools" :action="route('hub.show', $topic['slug'])" :query="$query" :sort="$sort" :filter="$filter" />

        <div data-lazy-section>
            <x-authority.featured-comparisons :title="$topic['title']" :comparisons="$topic['comparisons']" />
            <x-authority.featured-articles :title="$topic['title']" :articles="$topic['articles']" />

            <section class="info-panel topic-guide" aria-labelledby="beginner-guide-heading">
                <span class="eyebrow">Start here</span>
                <h2 id="beginner-guide-heading">Beginner's Guide to {{ $topic['title'] }}</h2>
                @foreach($topic['guide'] as $section)
                    <section><h3>{{ $section['heading'] }}</h3>@foreach($section['paragraphs'] as $paragraph)<p>{{ $paragraph }}</p>@endforeach</section>
                @endforeach
            </section>

            <section class="info-panel authority-use-cases" aria-labelledby="authority-use-cases-heading">
                <span class="eyebrow">Practical scenarios</span><h2 id="authority-use-cases-heading">Common Use Cases</h2>
                <div>@foreach($topic['authority']['use_cases'] as $useCase)<article><strong>{{ $useCase['audience'] }}</strong><p>{{ $useCase['scenario'] }}</p></article>@endforeach</div>
            </section>

            <section class="hub-reference-grid" aria-label="Practical topic reference">
                <article class="info-panel"><span class="eyebrow">Do this</span><h2>Best Practices</h2><ul>@foreach($topic['authority']['best_practices'] as $practice)<li>{{ $practice }}</li>@endforeach</ul></article>
                <article class="info-panel"><span class="eyebrow">Avoid this</span><h2>Common Mistakes</h2><ul>@foreach($topic['authority']['mistakes'] as $mistake)<li>{{ $mistake }}</li>@endforeach</ul></article>
            </section>

            <x-authority.glossary :title="$topic['title']" :terms="$topic['authority']['glossary']" />
            <x-authority.faq :title="$topic['title']" :faqs="$topic['authority']['faqs']" />

            <section class="info-panel" aria-labelledby="related-hubs-heading">
                <span class="eyebrow">Explore nearby topics</span><h2 id="related-hubs-heading">Related Topic Hubs</h2>
                <div class="hub-related-grid">@foreach($topic['related_hubs'] as $hub)<a href="{{ route('hub.show', $hub['slug']) }}"><strong>{{ $hub['title'] }}</strong><p>{{ $hub['description'] }}</p><small>Open topic hub →</small></a>@endforeach</div>
            </section>

            <x-authority.references :references="$topic['authority']['references']" />
            <x-blog.share :url="$canonicalUrl" :title="$topic['title']" label="Share this topic" />
            <x-authority.feedback :slug="'topic-'.$topic['slug']" />
            <x-editorial.reviewer-card :reviewer="$editorialMeta['reviewer']" />
            <x-editorial.author-card :author="$editorialMeta['author']" />
            <x-authority.cta tools-url="#authority-tools" :articles-url="route('blog.index')" :comparisons-url="route('compare.index')" />
        </div>
    </article>
@endsection
