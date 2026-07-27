@props(['recommendation'])

<article class="comparison-quality-winner">
    <small>🏆 {{ $recommendation['label'] }}</small>
    <strong>{{ $recommendation['winner'] }}</strong>
    <p>{{ $recommendation['reason'] }}</p>
</article>
