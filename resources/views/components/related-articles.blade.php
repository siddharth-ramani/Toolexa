@props(['articles', 'heading' => 'Helpful Articles', 'eyebrow' => 'Related articles', 'newTab' => false])

@if(count($articles))
    <section class="info-panel internal-links-panel" aria-labelledby="related-articles-heading">
        <span class="eyebrow">{{ $eyebrow }}</span>
        <h2 id="related-articles-heading">{{ $heading }}</h2>
        <div class="blog-related-grid">
            @foreach($articles as $relatedArticle)
                <a class="related-article-card" href="{{ route('blog.show', $relatedArticle['slug']) }}" @if($newTab) target="_blank" rel="noopener" @endif>
                    <span class="related-article-mark" aria-hidden="true">{{ strtoupper(substr($relatedArticle['category'], 0, 2)) }}</span>
                    <span class="related-article-content">
                        <span class="related-article-meta">{{ $relatedArticle['category'] }} · {{ $relatedArticle['reading_time'] }} min read</span>
                        <strong>{{ $relatedArticle['title'] }}</strong>
                        <small>{{ $relatedArticle['excerpt'] }}</small>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
@endif
