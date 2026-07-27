@extends('layouts.app')

@section('content')
    <article class="blog-article">
        <header class="tool-hero blog-article-hero">
            <span class="eyebrow">{{ $article['category'] }}</span>
            <h1>{{ $article['title'] }}</h1>
            <x-editorial.metadata-row :metadata="$editorialMeta" />
            <p>{{ $article['excerpt'] }}</p>
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

        <x-blog.share :url="$canonicalUrl" :title="$article['title']" />
        <x-blog.summary-box :items="$qualityContent['summary']" />
        <x-blog.toc :items="$toc" />

        <section class="blog-content info-panel">
            <div class="editorial-review-status" aria-label="Review process">
                <span>✓ Reviewed for accuracy</span>
                <span>✓ Last updated {{ \Carbon\Carbon::parse($editorialMeta['updated_at'])->format('F Y') }}</span>
                <span>✓ Educational purposes</span>
            </div>

            <section id="introduction" class="blog-section">
                <h2>Introduction</h2>
                @foreach($qualityContent['introduction'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </section>

            <section id="step-by-step-guide" class="blog-section">
                <h2>Step-by-Step Guide</h2>
                <ol class="blog-step-list">
                    @foreach($qualityContent['steps'] as $step)
                        <li><span>Step {{ $loop->iteration }}</span><p>{{ $step }}</p></li>
                    @endforeach
                </ol>
            </section>

            @foreach($qualityContent['sections'] as $section)
                <section id="{{ \Illuminate\Support\Str::slug($section['heading']) }}" class="blog-section">
                    <h2>{{ $section['heading'] }}</h2>
                    @foreach($section['paragraphs'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                    @if(isset($qualityContent['blocks'][$loop->index]))
                        <x-blog.info-block :block="$qualityContent['blocks'][$loop->index]" />
                    @endif
                </section>
            @endforeach

            <section id="common-mistakes" class="blog-section">
                <h2>Common Mistakes</h2>
                <ul class="blog-mistake-list">
                    @foreach($qualityContent['mistakes'] as $mistake)
                        <li>{{ $mistake }}</li>
                    @endforeach
                </ul>
            </section>
        </section>

        <x-blog.faq :faqs="$qualityContent['faqs']" :title="$article['title']" />
        <x-blog.related-content :tools="$relatedTools" :comparisons="$relatedComparisons" :articles="$relatedArticles" />
        <x-blog.references :references="$qualityContent['references']" />
        <x-blog.feedback :slug="$article['slug']" />

        <section class="info-panel editorial-history blog-freshness" aria-labelledby="content-freshness-heading">
            <span class="eyebrow">Content Freshness</span>
            <h2 id="content-freshness-heading">Review and version details</h2>
            <p><strong>Content history</strong> records meaningful editorial changes while the version number supports future revisions.</p>
            <dl>
                <div><dt>Last Updated</dt><dd>{{ \Carbon\Carbon::parse($editorialMeta['updated_at'])->format('F j, Y') }}</dd></div>
                <div><dt>Content Version</dt><dd>{{ $qualityContent['content_version'] }}</dd></div>
                <div><dt>Reviewed By</dt><dd>{{ $editorialMeta['reviewer']['name'] }}</dd></div>
            </dl>
            @foreach(array_merge($editorialMeta['history'], $qualityContent['version_history']) as $update)
                <article>
                    <time datetime="{{ $update['date'] }}">{{ \Carbon\Carbon::parse($update['date'])->format('F Y') }}</time>
                    <p>{{ $update['note'] }}</p>
                </article>
            @endforeach
        </section>

        <x-editorial.reviewer-card :reviewer="$editorialMeta['reviewer']" />
        <x-editorial.author-card :author="$editorialMeta['author']" />

        <section class="info-panel blog-bottom-cta" aria-labelledby="blog-cta-heading">
            <span class="eyebrow">Continue with Toolexa</span>
            <h2 id="blog-cta-heading">Turn what you learned into action</h2>
            <p>Apply the guide with a related free tool, or continue learning with another practical article.</p>
            <div>
                @if(count($relatedTools))
                    <a class="btn btn-primary" href="{{ url('tools/'.$relatedTools[0]['slug']) }}">Try Related Free Tools</a>
                @endif
                <a class="btn" href="{{ route('blog.index') }}">Explore More Articles</a>
            </div>
        </section>

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
