@extends('layouts.app')

@section('content')
    <div class="personal-dashboard" data-personal-dashboard>
        <header class="tool-hero dashboard-hero">
            <span class="eyebrow">Stored only on this device</span>
            <h1>Welcome Back</h1>
            <p>Continue where you left off. Your favorites, history, pins and collections stay privately in this browser—no login or database required.</p>
            <div class="dashboard-privacy-note"><span aria-hidden="true">LOCAL</span><strong>Your dashboard never leaves this device.</strong></div>
        </header>

        <section class="dashboard-stat-grid" aria-label="Dashboard statistics" data-dashboard-stats></section>

        <section class="info-panel dashboard-section" aria-labelledby="pinned-tools-heading">
            <div class="dashboard-section-head"><div><span class="eyebrow">Always first</span><h2 id="pinned-tools-heading">Pinned Tools</h2></div><a href="{{ route('search') }}">Find tools</a></div>
            <div class="dashboard-card-grid" data-dashboard-pinned></div>
        </section>

        <section class="info-panel dashboard-section" aria-labelledby="recently-used-heading">
            <div class="dashboard-section-head"><div><span class="eyebrow">Continue working</span><h2 id="recently-used-heading">Recently Used Tools</h2></div><small>Last 20 tools</small></div>
            <div class="dashboard-card-grid" data-dashboard-recent-tools></div>
        </section>

        <section class="dashboard-favorites-grid" aria-label="Saved favorites">
            <article class="info-panel dashboard-section"><span class="eyebrow">Saved</span><h2>Favorite Tools</h2><div class="dashboard-list" data-dashboard-favorite-tools></div></article>
            <article class="info-panel dashboard-section"><span class="eyebrow">Saved</span><h2>Favorite Articles</h2><div class="dashboard-list" data-dashboard-favorite-articles></div></article>
            <article class="info-panel dashboard-section"><span class="eyebrow">Saved</span><h2>Favorite Comparisons</h2><div class="dashboard-list" data-dashboard-favorite-comparisons></div></article>
        </section>

        <section class="info-panel dashboard-section" aria-labelledby="continue-reading-heading">
            <span class="eyebrow">Pick up your guide</span><h2 id="continue-reading-heading">Continue Reading</h2>
            <div data-dashboard-continue-reading></div>
        </section>

        <section class="info-panel dashboard-section" aria-labelledby="collections-heading">
            <div class="dashboard-section-head"><div><span class="eyebrow">Organize your toolkit</span><h2 id="collections-heading">Custom Collections</h2></div></div>
            <form class="dashboard-collection-form" data-collection-form>
                <label class="sr-only" for="collection-name">Collection name</label>
                <input id="collection-name" class="form-control" type="text" maxlength="60" placeholder="Example: Tax Tools" required>
                <button class="btn btn-primary" type="submit">Create Collection</button>
            </form>
            <div class="dashboard-collection-grid" data-dashboard-collections></div>
        </section>

        <section class="info-panel dashboard-section" aria-labelledby="recent-searches-heading">
            <span class="eyebrow">Search history</span><h2 id="recent-searches-heading">Recent Searches</h2>
            <div class="dashboard-search-list" data-dashboard-searches></div>
        </section>

        <section class="dashboard-data-panel dashboard-section" aria-labelledby="dashboard-data-heading">
            <div><span class="eyebrow">Portable and private</span><h2 id="dashboard-data-heading">Manage Dashboard Data</h2><p>Export a backup, restore one on another browser, or reset this device.</p></div>
            <div class="dashboard-data-actions">
                <button class="btn btn-primary" type="button" data-dashboard-export>Export JSON</button>
                <label class="btn" for="dashboard-import">Import JSON</label>
                <input id="dashboard-import" class="sr-only" type="file" accept="application/json,.json" data-dashboard-import>
                <button class="btn dashboard-reset-button" type="button" data-dashboard-reset>Reset Dashboard</button>
            </div>
            <p class="dashboard-data-status" data-dashboard-data-status aria-live="polite"></p>
        </section>

        <script type="application/json" data-dashboard-catalog>@json($toolCatalog, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
    </div>
@endsection
