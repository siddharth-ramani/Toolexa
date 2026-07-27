@extends('layouts.app')

@section('content')
    <article class="category-authority-page">
        <x-authority.hero :title="$landing['label']" :description="$landing['description']" :authority="$authority" :editorial-meta="$editorialMeta" />
        <x-authority.overview :title="$landing['label']" :paragraphs="$authority['overview']" />
        <x-authority.featured-tools :title="$landing['label']" :selections="$landing['featured']" />
        <x-authority.tool-directory :title="$landing['label']" :tools="$tools" :action="route('category.show', $category['slug'])" :query="$query" :sort="$sort" :filter="$filter" />

        <div data-lazy-section>
            <x-authority.featured-comparisons :title="$landing['label']" :comparisons="$comparisons" />
            <span class="visually-hidden">Popular guides for this category</span>
            <x-authority.featured-articles :title="$landing['label']" :articles="$relatedArticles" />

            <section class="info-panel category-introduction topic-guide" aria-labelledby="category-introduction-heading">
                <span class="eyebrow">Beginner Guide</span>
                <h2 id="category-introduction-heading">Beginner's Guide to {{ $landing['label'] }}</h2>
                @foreach($landing['introduction'] as $section)
                    <section><h3>{{ $section['heading'] }}</h3>@foreach($section['paragraphs'] as $paragraph)<p>{{ $paragraph }}</p>@endforeach</section>
                @endforeach
            </section>

            <section class="info-panel authority-use-cases" aria-labelledby="category-use-cases-heading">
                <span class="eyebrow">Practical scenarios</span><h2 id="category-use-cases-heading">Common Use Cases</h2>
                <div>@foreach($authority['use_cases'] as $useCase)<article><strong>{{ $useCase['audience'] }}</strong><p>{{ $useCase['scenario'] }}</p></article>@endforeach</div>
            </section>

            <section class="hub-reference-grid" aria-label="Category recommendations">
                <article class="info-panel"><span class="eyebrow">Do this</span><h2>Best Practices</h2><ul>@foreach($authority['best_practices'] as $practice)<li>{{ $practice }}</li>@endforeach</ul></article>
                <article class="info-panel"><span class="eyebrow">Avoid this</span><h2>Common Mistakes</h2><ul>@foreach($authority['mistakes'] as $mistake)<li>{{ $mistake }}</li>@endforeach</ul></article>
            </section>

            <x-authority.glossary :title="$landing['label']" :terms="$authority['glossary']" />

            <section class="info-panel category-trust" aria-labelledby="why-toolexa-heading">
                <span class="eyebrow">Simple by design</span><h2 id="why-toolexa-heading">Why use Toolexa?</h2>
                <div class="category-trust-grid">
                    @foreach([['FREE', 'Free', 'Use every tool without a subscription.'], ['OPEN', 'No signup', 'Start immediately without creating an account.'], ['SAFE', 'Secure', 'Privacy-conscious browser workflows where supported.'], ['FAST', 'Fast', 'Focused pages built for quick results.'], ['MOB', 'Mobile friendly', 'Responsive tools for phone, tablet and desktop.']] as [$icon, $title, $description])
                        <article><span class="tool-icon">{{ $icon }}</span><h3>{{ $title }}</h3><p>{{ $description }}</p></article>
                    @endforeach
                </div>
            </section>

            <x-authority.faq :title="$landing['label']" :faqs="$authority['faqs']" />

            @if(count($relatedCategories))
                <section class="info-panel category-related" aria-labelledby="related-categories-heading">
                    <span class="eyebrow">Explore nearby topics</span><h2 id="related-categories-heading">Related Categories</h2>
                    <div class="category-related-grid">
                        @foreach($relatedCategories as $relatedCategory)
                            <a href="{{ route('category.show', $relatedCategory['slug']) }}"><strong>{{ str_contains($relatedCategory['name'], 'Tools') ? $relatedCategory['name'] : $relatedCategory['name'].' Tools' }}</strong><span>{{ $relatedCategory['count'] }} tools</span><small>Explore category →</small></a>
                        @endforeach
                    </div>
                </section>
            @endif

            <x-authority.references :references="$authority['references']" />
            <x-blog.share :url="$canonicalUrl" :title="$landing['label']" label="Share this topic" />
            <x-authority.feedback :slug="'category-'.$category['slug']" />
            <x-editorial.reviewer-card :reviewer="$editorialMeta['reviewer']" />
            <x-editorial.author-card :author="$editorialMeta['author']" />
            <x-authority.cta tools-url="#authority-tools" :articles-url="route('blog.index')" :comparisons-url="route('compare.index')" />
        </div>
    </article>
@endsection
