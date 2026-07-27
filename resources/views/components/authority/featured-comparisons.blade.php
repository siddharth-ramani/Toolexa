@props(['title', 'comparisons'])

<section id="authority-comparisons" class="info-panel" aria-labelledby="authority-comparisons-heading">
    <span class="eyebrow">Understand the differences</span>
    <h2 id="authority-comparisons-heading">Featured {{ $title }} Comparisons</h2>
    @if(count($comparisons))
        <div class="hub-comparison-grid">
            @foreach($comparisons as $comparison)
                <a href="{{ route('compare.show', $comparison['slug']) }}"><span><b>{{ $comparison['left']['name'] }}</b><i>VS</i><b>{{ $comparison['right']['name'] }}</b></span><strong>{{ $comparison['title'] }}</strong><p>{{ $comparison['meta_description'] }}</p><small>Read comparison →</small></a>
            @endforeach
        </div>
    @else
        <p class="hub-section-empty">New comparisons will appear automatically when they are published for this topic.</p>
    @endif
</section>
