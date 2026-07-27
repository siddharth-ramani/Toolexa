@extends('layouts.app')

@section('content')
    <article class="comparison-page">
        <header class="tool-hero comparison-hero">
            <span class="eyebrow">{{ $comparison['left']['category'] }} comparison</span>
            <h1>{{ $comparison['title'] }}</h1>
            <x-editorial.metadata-row :metadata="$editorialMeta" type="comparison" />
            <p>{{ $comparison['introduction'] }}</p>
            <div class="comparison-hero-actions">
                @if($comparison['left']['tool_data'])
                    <a class="btn btn-primary" href="{{ url('tools/'.$comparison['left']['tool_data']['slug']) }}">Open {{ $comparison['left']['name'] }} Tool</a>
                @endif
                @if($comparison['right']['tool_data'])
                    <a class="btn" href="{{ url('tools/'.$comparison['right']['tool_data']['slug']) }}">Open {{ $comparison['right']['name'] }} Tool</a>
                @endif
            </div>
        </header>

        <x-blog.share :url="$canonicalUrl" :title="$comparison['title']" label="Share this comparison" />

        <section class="info-panel comparison-winners" aria-labelledby="quick-winner-heading">
            <span class="eyebrow">At a glance</span>
            <h2 id="quick-winner-heading">Quick Recommendation <span class="visually-hidden">(Quick Winner)</span></h2>
            <div class="comparison-winner-grid">
                @foreach($qualityContent['recommendations'] as $recommendation)
                    <x-comparison-quality.winner-card :recommendation="$recommendation" />
                @endforeach
            </div>
            <p class="comparison-winner-note">These recommendations apply only to the criterion shown. Neither option is a universal winner.</p>
        </section>

        <x-comparison-quality.summary-card :summary="$qualityContent['summary']" />

        <section class="info-panel" aria-labelledby="feature-comparison-heading">
            <span class="eyebrow">Side by side</span>
            <h2 id="feature-comparison-heading">{{ $comparison['title'] }} Feature Comparison</h2>
            <x-comparison-table :comparison="$comparison" />
        </section>

        <section class="comparison-explanations" aria-label="Detailed explanation">
            @foreach(['left', 'right'] as $side)
                @php($subject = $comparison[$side])
                @php($explanation = $qualityContent['explanations'][$side])
                <article class="info-panel comparison-subject">
                    <span class="eyebrow">Understanding {{ $subject['name'] }}</span>
                    <h2>What is {{ $subject['name'] }}?</h2>
                    <p>{{ $explanation['overview'] }}</p>
                    <h3>History and background</h3>
                    <p>{{ $explanation['history'] }}</p>
                    <h3>How {{ $subject['name'] }} works</h3>
                    <p>{{ $explanation['how_it_works'] }}</p>
                    <h3>Where it is used</h3>
                    <p>{{ $explanation['where_used'] }}</p>
                    <p class="comparison-context-note">{{ $explanation['context'] }}</p>
                    <x-comparison-quality.pros-cons :subject="$subject" />
                </article>
            @endforeach
        </section>

        <section class="info-panel comparison-real-examples" aria-labelledby="real-examples-heading">
            <span class="eyebrow">Practical scenarios</span>
            <h2 id="real-examples-heading">Real-World Examples</h2>
            <div>
                @foreach($qualityContent['examples'] as $example)
                    <article>
                        <span>{{ $example['option'] }}</span>
                        <h3>{{ $example['scenario'] }}</h3>
                        <p>{{ $example['explanation'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section aria-label="Decision guides">
            @foreach(['left', 'right'] as $side)
                <x-comparison-quality.decision-guide :subject="$comparison[$side]" :decision="$qualityContent['decisions'][$side]" />
            @endforeach
        </section>

        <section class="info-panel comparison-mistakes" aria-labelledby="comparison-mistakes-heading">
            <span class="eyebrow">Avoid these issues</span>
            <h2 id="comparison-mistakes-heading">Common Mistakes</h2>
            <ol>
                @foreach($qualityContent['mistakes'] as $mistake)<li>{{ $mistake }}</li>@endforeach
            </ol>
        </section>

        <x-comparison-quality.faq :faqs="$qualityContent['faqs']" :title="$comparison['title']" />
        <x-comparison-quality.related-content :tools="$comparison['related_tools']" :articles="$comparison['related_articles']" :comparisons="$qualityContent['related_comparisons']" />
        <x-comparison-quality.references :references="$qualityContent['references']" />
        <x-comparison-quality.feedback :slug="$comparison['slug']" />

        <section class="info-panel editorial-history comparison-freshness" aria-labelledby="comparison-freshness-heading">
            <span class="eyebrow">Content Freshness</span>
            <h2 id="comparison-freshness-heading">Review and version details</h2>
            <dl>
                <div><dt>Published</dt><dd>{{ \Carbon\Carbon::parse($qualityContent['published_at'])->format('F j, Y') }}</dd></div>
                <div><dt>Last Updated</dt><dd>{{ \Carbon\Carbon::parse($editorialMeta['updated_at'])->format('F j, Y') }}</dd></div>
                <div><dt>Content Version</dt><dd>{{ $qualityContent['content_version'] }}</dd></div>
            </dl>
            @foreach(array_merge($editorialMeta['history'], $qualityContent['version_history']) as $update)
                <article><time datetime="{{ $update['date'] }}">{{ \Carbon\Carbon::parse($update['date'])->format('F Y') }}</time><p>{{ $update['note'] }}</p></article>
            @endforeach
        </section>

        <x-editorial.reviewer-card :reviewer="$editorialMeta['reviewer']" />
        <x-editorial.author-card :author="$editorialMeta['author']" />

        <section class="info-panel comparison-bottom-cta" aria-labelledby="comparison-cta-heading">
            <span class="eyebrow">Continue exploring</span>
            <h2 id="comparison-cta-heading">Make your decision with practical resources</h2>
            <div>
                @if(count($comparison['related_tools']))
                    <a class="btn btn-primary" href="{{ url('tools/'.$comparison['related_tools'][0]['slug']) }}">Explore Related Tools</a>
                @endif
                <a class="btn" href="{{ route('blog.index') }}">Read Related Articles</a>
                <a class="btn" href="{{ route('compare.index') }}">View More Comparisons</a>
            </div>
        </section>
    </article>
@endsection
