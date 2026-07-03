@extends('layouts.app')

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Search</span>
        <h1>Search Tools</h1>
        <p>Find calculators and utility tools by name, category or keyword.</p>
    </section>

    <section class="form-panel search-panel">
        <form method="GET" action="{{ route('search') }}" class="search-form">
            <div>
                <label for="q">Search keyword</label>
                <input id="q" class="form-control" type="search" name="q" value="{{ $query }}" placeholder="GST, EMI, QR, password">
            </div>

            <div>
                <label for="category">Category</label>
                <select id="category" class="form-control" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['slug'] }}" @selected($selectedCategory === $category['slug'])>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </section>

    <section class="info-panel">
        <span class="eyebrow">{{ $tools->total() }} found</span>
        <h2>{{ $query ? 'Results for "'.$query.'"' : 'All Tools' }}</h2>

        @if($tools->count())
            <div class="tool-grid">
                @foreach($tools as $tool)
                    <a class="card tool-card" href="{{ url('tools/'.$tool['slug']) }}">
                        <span class="tool-icon">{{ $tool['icon'] }}</span>
                        <h3>{{ $tool['name'] }}</h3>
                        <p>{{ $tool['desc'] }}</p>
                        <span class="btn btn-primary btn-sm">{{ $tool['category'] }}</span>
                    </a>
                @endforeach
            </div>

            {{ $tools->links('partials.pagination') }}
        @else
            <p class="empty-state">No tools found. Try a different keyword or category.</p>
        @endif
    </section>

    @if(isset($articles) && $articles->count())
        <section class="info-panel search-articles-panel">
            <div class="section-head">
                <div>
                    <span class="eyebrow">{{ $articles->count() }} articles</span>
                    <h2>Related Blog Articles</h2>
                </div>
                <a class="btn btn-sm" href="{{ route('blog.index') }}">View Blog</a>
            </div>

            <div class="search-article-list">
                @foreach($articles as $article)
                    <a class="search-article-card" href="{{ route('blog.show', $article['slug']) }}">
                        <span class="search-article-badge">{{ $article['category'] }}</span>
                        <span class="search-article-body">
                            <strong>{{ $article['title'] }}</strong>
                            <small>{{ $article['excerpt'] }}</small>
                            <span>{{ $article['reading_time'] }} min read</span>
                        </span>
                        <span class="search-article-arrow" aria-hidden="true">›</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
