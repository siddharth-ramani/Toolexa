@extends('layouts.app')

@section('content')
    <header class="tool-hero hub-index-hero">
        <span class="eyebrow">Learn and explore</span>
        <h1>Toolexa Topic Hubs</h1>
        <p>Comprehensive starting points that connect free tools, beginner guides, comparisons, articles and practical reference material.</p>
    </header>

    <section class="info-panel" aria-labelledby="topic-hub-list-heading">
        <span class="eyebrow">Authority guides</span>
        <h2 id="topic-hub-list-heading">Explore Every Topic</h2>
        <div class="hub-index-grid">
            @foreach($hubs as $hub)
                <a href="{{ route('hub.show', $hub['slug']) }}">
                    <span class="tool-icon" aria-hidden="true">{{ strtoupper(substr($hub['title'], 0, 3)) }}</span>
                    <strong>{{ $hub['title'] }}</strong>
                    <p>{{ $hub['description'] }}</p>
                    <small>{{ $hub['tool_count'] }} tools · Open hub →</small>
                </a>
            @endforeach
        </div>
    </section>
@endsection
