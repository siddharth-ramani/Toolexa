@props(['article'])

<article class="premium-article-card">
    <a href="{{ route('blog.show', $article['slug']) }}">
        <span class="premium-article-mark" aria-hidden="true">{{ strtoupper(substr($article['category'], 0, 2)) }}</span>
        <small>{{ $article['category'] }}</small>
        <h3>{{ $article['title'] }}</h3>
        <p>{{ $article['excerpt'] }}</p>
        <footer><span>{{ $article['reading_time'] }} min read</span><b>Read article →</b></footer>
    </a>
</article>
