@extends('layouts.app')

@section('content')
    <article class="comparison-page">
        <header class="tool-hero comparison-hero">
            <span class="eyebrow">Complete comparison</span>
            <h1>{{ $comparison['title'] }}</h1>
            <p>{{ $comparison['introduction'] }}</p>
            <div class="comparison-hero-actions">
                @if($comparison['left']['tool_data'])
                    <a class="btn btn-primary" href="{{ url('tools/'.$comparison['left']['tool_data']['slug']) }}">Open {{ $comparison['left']['name'] }} Tool</a>
                @endif
                @if($comparison['right']['tool_data'])
                    <a class="btn" href="{{ url('tools/'.$comparison['right']['tool_data']['slug']) }}">Open {{ $comparison['right']['name'] }} Tool</a>
                @endif
            </div>
        </header>

        @if(count($comparison['winners']))
            <section class="info-panel comparison-winners" aria-labelledby="quick-winner-heading">
                <span class="eyebrow">At a glance</span>
                <h2 id="quick-winner-heading">Quick Winner</h2>
                <div class="comparison-winner-grid">
                    @foreach($comparison['winners'] as $winner)
                        <article>
                            <small>{{ $winner['label'] }}</small>
                            <strong>{{ $winner['winner'] }}</strong>
                            <p>{{ $winner['value'] }}</p>
                        </article>
                    @endforeach
                </div>
                <p class="comparison-winner-note">A winner reflects the specific criterion shown—not an overall recommendation for every situation.</p>
            </section>
        @endif

        <section class="info-panel" aria-labelledby="feature-comparison-heading">
            <span class="eyebrow">Side by side</span>
            <h2 id="feature-comparison-heading">{{ $comparison['title'] }} Feature Comparison</h2>
            <x-comparison-table :comparison="$comparison" />
        </section>

        <section class="comparison-explanations" aria-label="Detailed explanation">
            @foreach(['left', 'right'] as $side)
                @php($subject = $comparison[$side])
                <article class="info-panel comparison-subject">
                    <span class="eyebrow">Understanding {{ $subject['name'] }}</span>
                    <h2>What is {{ $subject['name'] }}?</h2>
                    <p>{{ $subject['summary'] }}</p>
                    <div class="comparison-pros-cons">
                        <section>
                            <h3>Advantages</h3>
                            <ul>@foreach($subject['advantages'] as $item)<li>{{ $item }}</li>@endforeach</ul>
                        </section>
                        <section>
                            <h3>Disadvantages</h3>
                            <ul>@foreach($subject['disadvantages'] as $item)<li>{{ $item }}</li>@endforeach</ul>
                        </section>
                    </div>
                    <h3>Best use cases</h3>
                    <ul>@foreach($subject['use_cases'] as $item)<li>{{ $item }}</li>@endforeach</ul>
                </article>
            @endforeach
        </section>

        @foreach(['left', 'right'] as $side)
            @php($subject = $comparison[$side])
            <section class="info-panel comparison-choice">
                <span class="eyebrow">Decision guide</span>
                <h2>When Should You Choose {{ $subject['name'] }}?</h2>
                <p>Choose {{ $subject['name'] }} when your priority matches these practical situations:</p>
                <ul>
                    @foreach($subject['use_cases'] as $useCase)<li>{{ $useCase }}</li>@endforeach
                    @foreach(array_slice($subject['advantages'], 0, 2) as $advantage)<li>{{ $advantage }}</li>@endforeach
                </ul>
                @if($subject['tool_data'])
                    <p>Try the <a href="{{ url('tools/'.$subject['tool_data']['slug']) }}">{{ $subject['tool_data']['name'] }}</a> with a realistic example before making the final choice.</p>
                @endif
            </section>
        @endforeach

        <section class="info-panel comparison-mistakes" aria-labelledby="comparison-mistakes-heading">
            <span class="eyebrow">Avoid these issues</span>
            <h2 id="comparison-mistakes-heading">Common Mistakes</h2>
            <ol>
                @foreach($comparison['mistakes'] as $mistake)<li>{{ $mistake }}</li>@endforeach
            </ol>
        </section>

        <section class="info-panel faq-panel" aria-labelledby="comparison-faq-heading">
            <span class="eyebrow">Questions answered</span>
            <h2 id="comparison-faq-heading">{{ $comparison['title'] }} FAQs</h2>
            @foreach($comparison['faqs'] as $faq)
                <details><summary>{{ $faq['question'] }}</summary><p>{{ $faq['answer'] }}</p></details>
            @endforeach
        </section>

        <x-related-tools :tools="$comparison['related_tools']" heading="Tools for this comparison" />
        <x-related-articles :articles="$comparison['related_articles']" heading="Learn more about these options" />

        <nav class="comparison-back" aria-label="Comparison navigation">
            <a class="btn" href="{{ route('compare.index') }}">Browse All Comparisons</a>
        </nav>
    </article>
@endsection
