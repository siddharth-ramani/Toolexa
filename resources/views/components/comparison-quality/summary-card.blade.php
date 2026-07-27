@props(['summary'])

<section class="info-panel comparison-quality-summary" aria-labelledby="quick-summary-heading">
    <span class="eyebrow">Quick Summary</span>
    <h2 id="quick-summary-heading">Which option fits you?</h2>
    <div>
        @foreach(['left', 'right'] as $side)
            <article>
                <h3>{{ $summary[$side]['title'] }}</h3>
                <p>{{ $summary[$side]['text'] }}</p>
            </article>
        @endforeach
    </div>
    <p class="comparison-unbiased-note">{{ $summary['context'] }}</p>
</section>
