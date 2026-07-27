@props(['score', 'health'])

<div class="audit-score audit-score-{{ \Illuminate\Support\Str::slug($health) }}" style="--audit-score: {{ (int) $score }}">
    <strong>{{ $score }}</strong><span>{{ $health }}</span>
</div>
