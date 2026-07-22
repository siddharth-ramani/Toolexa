@extends('layouts.app')

@section('content')
    <header class="tool-hero category-landing-hero">
        <div class="category-hero-copy">
            <span class="eyebrow">Free online tools</span>
            <h1>{{ $landing['label'] }}</h1>
            <p>{{ $landing['description'] }}</p>
            <div class="category-hero-stats">
                <span><strong>{{ $allToolCount }}</strong> available tools</span>
                @if(count($landing['featured']))
                    <a href="{{ url('tools/'.$landing['featured'][0]['tool']['slug']) }}">
                        Featured: <strong>{{ $landing['featured'][0]['tool']['name'] }}</strong>
                    </a>
                @endif
            </div>
        </div>

        <form class="category-search" method="GET" action="{{ route('category.show', $category['slug']) }}" role="search">
            <label for="category-search">Search within {{ $landing['label'] }}</label>
            <div>
                <input id="category-search" class="form-control" type="search" name="q" value="{{ $query }}" placeholder="Search {{ strtolower($category['name']) }}...">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
            @if($query !== '')
                <a href="{{ route('category.show', $category['slug']) }}">Clear search</a>
            @endif
        </form>
    </header>

    @if(count($landing['featured']))
        <section class="info-panel category-featured" aria-labelledby="featured-tools-heading">
            <span class="eyebrow">Category highlights</span>
            <h2 id="featured-tools-heading">Featured {{ $landing['label'] }}</h2>
            <div class="category-featured-grid">
                @foreach($landing['featured'] as $featured)
                    <x-category-tool-card :tool="$featured['tool']" :badge="$featured['label']" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="info-panel category-introduction" aria-labelledby="category-introduction-heading">
        <span class="eyebrow">Category guide</span>
        <h2 id="category-introduction-heading">About {{ $landing['label'] }}</h2>
        @foreach($landing['introduction'] as $section)
            <section>
                <h3>{{ $section['heading'] }}</h3>
                @foreach($section['paragraphs'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </section>
        @endforeach
    </section>

    <section id="all-tools" class="info-panel category-tools-panel" aria-labelledby="all-tools-heading">
        <div class="section-head category-tools-head">
            <div>
                <span class="eyebrow">{{ $tools->total() }} {{ $query ? 'matching' : 'available' }} tools</span>
                <h2 id="all-tools-heading">All {{ $landing['label'] }}</h2>
            </div>
        </div>

        @if($tools->count())
            <div class="category-tool-grid">
                @foreach($tools as $tool)
                    <x-category-tool-card :tool="$tool" />
                @endforeach
            </div>
            {{ $tools->links('partials.pagination') }}
        @else
            <div class="category-empty-state">
                <h3>No tools found</h3>
                <p>Try a shorter search or browse every tool in this category.</p>
                <a class="btn btn-primary" href="{{ route('category.show', $category['slug']) }}">View all tools</a>
            </div>
        @endif
    </section>

    <x-related-articles :articles="$relatedArticles" heading="Popular guides for this category" />

    <section class="info-panel category-trust" aria-labelledby="why-toolexa-heading">
        <span class="eyebrow">Simple by design</span>
        <h2 id="why-toolexa-heading">Why use Toolexa?</h2>
        <div class="category-trust-grid">
            @foreach([
                ['FREE', 'Free', 'Use every tool without a subscription.'],
                ['OPEN', 'No signup', 'Start immediately without creating an account.'],
                ['SAFE', 'Secure', 'Privacy-conscious browser workflows where supported.'],
                ['FAST', 'Fast', 'Focused pages built for quick results.'],
                ['MOB', 'Mobile friendly', 'Responsive tools for phone, tablet and desktop.'],
            ] as [$icon, $title, $description])
                <article><span class="tool-icon">{{ $icon }}</span><h3>{{ $title }}</h3><p>{{ $description }}</p></article>
            @endforeach
        </div>
    </section>

    <section class="info-panel faq-panel category-faq" aria-labelledby="category-faq-heading">
        <span class="eyebrow">Frequently asked questions</span>
        <h2 id="category-faq-heading">{{ $landing['label'] }} FAQs</h2>
        @foreach($landing['faqs'] as $faq)
            <details>
                <summary>{{ $faq['question'] }}</summary>
                <p>{{ $faq['answer'] }}</p>
            </details>
        @endforeach
    </section>

    @if(count($relatedCategories))
        <section class="info-panel category-related" aria-labelledby="related-categories-heading">
            <span class="eyebrow">Explore more</span>
            <h2 id="related-categories-heading">Related Categories</h2>
            <div class="category-related-grid">
                @foreach($relatedCategories as $relatedCategory)
                    <a href="{{ route('category.show', $relatedCategory['slug']) }}">
                        <strong>{{ str_contains($relatedCategory['name'], 'Tools') ? $relatedCategory['name'] : $relatedCategory['name'].' Tools' }}</strong>
                        <span>{{ $relatedCategory['count'] }} tools</span>
                        <small>Explore category →</small>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
