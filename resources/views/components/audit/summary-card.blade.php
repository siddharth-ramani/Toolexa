@props(['label', 'value', 'tone' => 'neutral'])

<article class="audit-summary-card audit-tone-{{ $tone }}">
    <span>{{ $label }}</span>
    <strong>{{ $value }}</strong>
</article>
