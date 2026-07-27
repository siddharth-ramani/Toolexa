@props(['title', 'articles'])

<section id="authority-articles" class="info-panel" aria-labelledby="authority-articles-heading">
    <span class="eyebrow">Popular and educational</span>
    <h2 id="authority-articles-heading">Featured {{ $title }} Articles</h2>
    @if(count($articles))
        <div class="premium-article-grid">@foreach(array_slice($articles, 0, 6) as $article)<x-home-article-card :article="$article" />@endforeach</div>
    @else
        <p class="hub-section-empty">Relevant educational articles will appear here automatically.</p>
    @endif
</section>
