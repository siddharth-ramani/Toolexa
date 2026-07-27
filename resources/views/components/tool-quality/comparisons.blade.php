@props(['comparisons'])
@if(count($comparisons))
<section class="info-panel tool-quality-section" aria-labelledby="tool-comparisons-heading">
    <span class="eyebrow">Compare options</span><h2 id="tool-comparisons-heading">Related Comparisons</h2>
    <div class="quality-link-grid">
        @foreach($comparisons as $comparison)
            <a href="{{ route('compare.show', $comparison['slug']) }}"><strong>{{ $comparison['title'] }}</strong><span>{{ $comparison['meta_description'] }}</span></a>
        @endforeach
    </div>
</section>
@endif
