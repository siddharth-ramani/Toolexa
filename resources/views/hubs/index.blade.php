@extends('layouts.app')

@section('content')
    <header class="tool-hero hub-index-hero">
        <span class="eyebrow">Learn and explore</span>
        <h1>Toolexa Topic Hubs</h1>
        <p>Comprehensive starting points that connect free tools, beginner guides, comparisons, articles and practical reference material.</p>
        <div class="topic-hub-stats">
            <span><strong>{{ count($hubs) }}</strong><span>Authority Topics</span></span>
            <span><strong>{{ collect($hubs)->sum('tool_count') }}</strong><span>Connected Tools</span></span>
        </div>
    </header>

    <section class="info-panel authority-overview" aria-labelledby="topic-directory-overview-heading">
        <span class="eyebrow">How to use this directory</span>
        <h2 id="topic-directory-overview-heading">Start with a topic, then move from learning to action</h2>
        <p>Each Toolexa topic hub combines a plain-language overview, a detailed beginner guide, featured and complete tool directories, relevant comparisons, educational articles, real-world use cases, best practices, common mistakes and essential terminology. Choose the subject closest to your goal rather than beginning with an unfamiliar tool name.</p>
        <p>The directory updates as Toolexa publishes new tools and connected resources. Every hub remains independently editable, allowing its examples, references and guidance to reflect the needs of that subject while retaining a consistent, accessible layout.</p>
    </section>

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
