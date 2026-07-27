@props(['title', 'description', 'authority', 'editorialMeta'])

<header class="tool-hero authority-hero">
    <span class="eyebrow">Toolexa authority guide</span>
    <h1>{{ $title }}</h1>
    <p>{{ $description }}</p>
    <x-editorial.metadata-row :metadata="$editorialMeta" />
    <div class="topic-hub-stats">
        <a href="#authority-tools"><strong>{{ $authority['tool_count'] }}</strong><span>Tools</span></a>
        <a href="#authority-articles"><strong>{{ $authority['article_count'] }}</strong><span>Articles</span></a>
        <a href="#authority-comparisons"><strong>{{ $authority['comparison_count'] }}</strong><span>Comparisons</span></a>
        <span><strong>{{ \Carbon\Carbon::parse($authority['last_updated'])->format('M Y') }}</strong><span>Last Updated</span></span>
    </div>
</header>
