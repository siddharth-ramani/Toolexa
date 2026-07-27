@props(['tool', 'steps', 'note' => null])
<section class="info-panel tool-quality-section" aria-labelledby="tool-steps-heading">
    <div class="quality-section-heading">
        <span class="eyebrow">Step by step</span>
        <h2 id="tool-steps-heading">How to Use {{ $tool['name'] }}</h2>
        <p>Follow these simple steps to get a clear and accurate result.</p>
    </div>
    <ol class="quality-step-grid">
        @foreach($steps as $step)
            @php
                $title = is_array($step) ? ($step['title'] ?? 'Complete this step') : 'Step '.$loop->iteration;
                $description = is_array($step) ? ($step['description'] ?? '') : $step;
            @endphp
            <li>
                <span class="quality-step-number" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <small>Step {{ $loop->iteration }}</small>
                    <h3>{{ $title }}</h3>
                    <p>{{ $description }}</p>
                </div>
            </li>
        @endforeach
    </ol>
    @if($note)
        <aside class="quality-step-note" aria-label="Accuracy tip">
            <strong>Accuracy tip</strong>
            <p>{{ $note }}</p>
        </aside>
    @endif
</section>
