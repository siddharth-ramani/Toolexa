@props(['tools', 'articles', 'comparisons'])

<div data-lazy-section>
    <x-related-tools :tools="$tools" heading="Tools for this comparison" />
    <x-related-articles :articles="$articles" heading="Learn more about these options" />

    @if(count($comparisons))
        <section class="info-panel" aria-labelledby="related-comparisons-heading">
            <span class="eyebrow">Continue comparing</span>
            <h2 id="related-comparisons-heading">Related Comparisons</h2>
            <div class="comparison-related-grid">
                @foreach($comparisons as $item)
                    <a href="{{ route('compare.show', $item['slug']) }}">
                        <strong>{{ $item['title'] }}</strong>
                        <span>{{ $item['meta_description'] }}</span>
                        <small>View comparison →</small>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
