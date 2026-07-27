@props(['tools', 'comparisons', 'articles'])

<div class="blog-related-content" data-lazy-section>
    <x-related-tools :tools="$tools" heading="Try these Toolexa tools" />

    @if(count($comparisons))
        <section class="info-panel" aria-labelledby="related-comparisons-heading">
            <span class="eyebrow">Compare options</span>
            <h2 id="related-comparisons-heading">Related Comparisons</h2>
            <div class="blog-comparison-grid">
                @foreach($comparisons as $comparison)
                    <a href="{{ route('compare.show', $comparison['slug']) }}">
                        <strong>{{ $comparison['title'] }}</strong>
                        <span>{{ $comparison['meta_description'] }}</span>
                        <small>Read comparison →</small>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <x-related-articles :articles="$articles" heading="Keep Reading" />
</div>
