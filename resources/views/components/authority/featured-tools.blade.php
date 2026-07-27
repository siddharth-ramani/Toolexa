@props(['title', 'selections'])

@if(count($selections))
    <section class="info-panel" aria-labelledby="authority-featured-tools-heading">
        <span class="eyebrow">Recommended starting points</span>
        <h2 id="authority-featured-tools-heading">Featured {{ $title }}</h2>
        <div class="category-featured-grid">
            @foreach($selections as $selection)
                <x-category-tool-card :tool="$selection['tool']" :badge="$selection['label']" />
            @endforeach
        </div>
    </section>
@endif
