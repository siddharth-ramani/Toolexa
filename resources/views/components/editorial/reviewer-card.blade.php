@props(['reviewer'])

<aside class="editorial-review-card" aria-label="Content reviewer">
    <span aria-hidden="true">✓</span>
    <div><strong>Reviewed by {{ $reviewer['name'] }}</strong><small>{{ $reviewer['role'] }} · {{ $reviewer['bio'] }}</small></div>
</aside>
