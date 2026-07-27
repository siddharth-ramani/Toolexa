@props(['subject', 'decision'])

<section class="info-panel comparison-choice" data-decision-guide>
    <span class="eyebrow">Interactive decision checklist</span>
    <h2>Choose {{ $subject['name'] }} if…</h2>
    <div class="comparison-decision-checks">
        @foreach($decision['choose_if'] as $index => $item)
            <label>
                <input type="checkbox" data-decision-check>
                <span>{{ $item }}</span>
            </label>
        @endforeach
    </div>
    <p class="comparison-decision-status" data-decision-status aria-live="polite">Select the statements that match your needs.</p>
    <h3>Reconsider {{ $subject['name'] }} if…</h3>
    <ul>@foreach($decision['reconsider_if'] as $item)<li>{{ $item }}</li>@endforeach</ul>
    <p><strong>Alternative:</strong> {{ $decision['alternative'] }}</p>
</section>
