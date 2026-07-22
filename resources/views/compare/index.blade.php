@extends('layouts.app')

@section('content')
    <header class="tool-hero comparison-index-hero">
        <span class="eyebrow">Toolexa comparisons</span>
        <h1>Compare Tools, Formats and Calculators</h1>
        <p>Understand important differences, strengths and tradeoffs before choosing a format, financial approach or browser tool.</p>
    </header>

    <section class="info-panel" aria-labelledby="all-comparisons-heading">
        <span class="eyebrow">Comparison library</span>
        <h2 id="all-comparisons-heading">Explore Comparisons</h2>
        <div class="comparison-index-grid">
            @foreach($comparisons as $comparison)
                <a href="{{ route('compare.show', $comparison['slug']) }}">
                    <span class="comparison-index-names">
                        <b>{{ $comparison['left']['name'] }}</b><i>VS</i><b>{{ $comparison['right']['name'] }}</b>
                    </span>
                    <strong>{{ $comparison['title'] }}</strong>
                    <p>{{ $comparison['meta_description'] }}</p>
                    <small>View full comparison →</small>
                </a>
            @endforeach
        </div>
    </section>
@endsection
