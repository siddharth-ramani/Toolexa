@props(['tools', 'heading' => 'Related Tools', 'eyebrow' => 'Related tools', 'newTab' => false])

@if(count($tools))
    <section class="info-panel internal-links-panel" aria-labelledby="related-tools-heading">
        <span class="eyebrow">{{ $eyebrow }}</span>
        <h2 id="related-tools-heading">{{ $heading }}</h2>
        <div class="mini-tool-grid">
            @foreach($tools as $tool)
                @include('partials.tool-card', ['tool' => $tool, 'newTab' => $newTab])
            @endforeach
        </div>
    </section>
@endif
