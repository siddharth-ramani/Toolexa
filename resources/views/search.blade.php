@extends('layouts.app')

@section('content')
    <header class="tool-hero smart-search-hero">
        <span class="eyebrow">Intelligent search</span>
        <h1>Find tools, articles and categories</h1>
        <p>Search the complete Toolexa library instantly. Spelling mistakes are welcome.</p>
    </header>

    <section class="smart-search-shell" data-smart-search data-endpoint="{{ route('search.api') }}">
        <div class="smart-search-bar">
            <label for="smart-search-input">What are you looking for?</label>
            <div class="smart-search-input-wrap">
                <span aria-hidden="true">⌕</span>
                <input
                    id="smart-search-input"
                    class="form-control"
                    type="search"
                    value="{{ $query }}"
                    placeholder="Try GST, passwrod or json formater"
                    autocomplete="off"
                    aria-autocomplete="list"
                    aria-controls="smart-search-results"
                    aria-expanded="{{ $query ? 'true' : 'false' }}"
                    data-search-input
                >
                <span class="smart-search-loader" data-search-loader aria-hidden="true"></span>
            </div>

            <div class="smart-search-filters" role="tablist" aria-label="Filter search results">
                @foreach(['all' => 'All', 'tools' => 'Tools', 'articles' => 'Articles', 'categories' => 'Categories'] as $value => $label)
                    <button type="button" role="tab" data-search-filter="{{ $value }}" aria-selected="{{ $selectedFilter === $value ? 'true' : 'false' }}" class="{{ $selectedFilter === $value ? 'active' : '' }}">{{ $label }}</button>
                @endforeach
            </div>

            <p class="sr-only" aria-live="polite" data-search-status></p>
        </div>

        <div class="smart-search-discovery" data-search-discovery @if($query) hidden @endif>
            <section>
                <h2>Trending searches</h2>
                <div class="search-chip-list">
                    @foreach($trendingSearches as $search)
                        <button type="button" data-search-chip="{{ $search }}">{{ $search }}</button>
                    @endforeach
                </div>
            </section>
            <section data-recent-searches hidden>
                <div class="search-section-heading">
                    <h2>Recent searches</h2>
                    <button type="button" data-clear-recent>Clear</button>
                </div>
                <div class="search-chip-list" data-recent-list></div>
            </section>
        </div>

        <div id="smart-search-results" class="smart-search-results" role="listbox" aria-label="Search suggestions" data-search-results @if(!$query) hidden @endif>
            @foreach(['tools' => 'Tools', 'articles' => 'Articles', 'categories' => 'Categories'] as $group => $heading)
                @if(count($initialResults['groups'][$group]))
                    <section class="smart-search-group" data-result-group="{{ $group }}">
                        <h2>{{ $heading }}</h2>
                        <div>
                            @foreach($initialResults['groups'][$group] as $item)
                                <x-search-result-card :item="$item" />
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        </div>

        <div class="smart-search-empty" data-search-empty @if(!$query || $initialResults['total']) hidden @endif>
            <span class="tool-icon" aria-hidden="true">?</span>
            <h2>No results found</h2>
            <p>Try a shorter phrase, check another spelling, or explore these popular recommendations.</p>
            <div class="smart-search-popular-grid">
                <section>
                    <h3>Popular Tools</h3>
                    @foreach($popularContent['tools'] as $tool)
                        <a href="{{ url('tools/'.$tool['slug']) }}">{{ $tool['name'] }}</a>
                    @endforeach
                </section>
                <section>
                    <h3>Popular Articles</h3>
                    @foreach($popularContent['articles'] as $article)
                        <a href="{{ route('blog.show', $article['slug']) }}">{{ $article['title'] }}</a>
                    @endforeach
                </section>
            </div>
        </div>

        <noscript>
            <form class="smart-search-noscript" method="GET" action="{{ route('search') }}">
                <label for="fallback-search">Search Toolexa</label>
                <input id="fallback-search" class="form-control" type="search" name="q" value="{{ $query }}" required>
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </noscript>

        @php
            $searchState = ['query' => $query, 'filter' => $selectedFilter, 'results' => $initialResults];
        @endphp
        <script type="application/json" data-search-state>@json($searchState, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
    </section>
@endsection
