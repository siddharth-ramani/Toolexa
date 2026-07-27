@extends('layouts.app')

@section('content')
    <article class="author-profile-page">
        <header class="tool-hero author-profile-hero">
            <img src="{{ asset($author['photo']) }}" alt="{{ $author['name'] }}" width="112" height="112">
            <div>
                <span class="eyebrow">{{ $author['role'] }}</span>
                <h1>{{ $author['name'] }}</h1>
                <p>{{ $author['bio'] }}</p>
                <span>{{ $author['years_experience'] }}+ years of combined editorial experience</span>
            </div>
        </header>

        <section class="info-panel">
            <h2>Biography</h2>
            <p>{{ $author['biography'] }}</p>
            <h2>Mission</h2>
            <p>{{ $author['mission'] }}</p>
        </section>

        <section class="info-panel">
            <h2>Areas of Expertise</h2>
            <ul class="trust-checklist">@foreach($author['expertise'] as $expertise)<li>{{ $expertise }}</li>@endforeach</ul>
        </section>

        <section class="info-panel author-content-section">
            <h2>Latest Articles</h2>
            <div class="author-content-grid">
                @foreach($articles as $article)
                    <a href="{{ route('blog.show', $article['slug']) }}"><strong>{{ $article['title'] }}</strong><span>{{ $article['category'] }} · {{ $article['reading_time'] }} min read</span></a>
                @endforeach
            </div>
        </section>

        <section class="info-panel author-content-section">
            <h2>Latest Tools Reviewed</h2>
            <div class="author-content-grid">
                @foreach($tools as $tool)
                    <a href="{{ url('tools/'.$tool['slug']) }}"><strong>{{ $tool['name'] }}</strong><span>{{ $tool['category'] }}</span></a>
                @endforeach
            </div>
        </section>

        <section class="info-panel author-content-section">
            <h2>Latest Comparisons</h2>
            <div class="author-content-grid">
                @foreach($comparisons as $comparison)
                    <a href="{{ route('compare.show', $comparison['slug']) }}"><strong>{{ $comparison['title'] }}</strong><span>Detailed comparison</span></a>
                @endforeach
            </div>
        </section>
    </article>
@endsection
