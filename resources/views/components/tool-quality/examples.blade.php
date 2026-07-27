@props(['tool', 'examples'])
<section class="info-panel tool-quality-section" aria-labelledby="tool-examples-heading">
    <span class="eyebrow">Practical scenarios</span>
    <h2 id="tool-examples-heading">{{ $tool['name'] }} Examples</h2>
    <div class="quality-example-grid">
        @foreach($examples as $example)
            <article>
                <h3>{{ $example['title'] }}</h3>
                <dl>
                    <div><dt>Input</dt><dd>{{ $example['input'] }}</dd></div>
                    <div><dt>Process</dt><dd>{{ $example['action'] }}</dd></div>
                    <div><dt>Outcome</dt><dd>{{ $example['outcome'] }}</dd></div>
                </dl>
            </article>
        @endforeach
    </div>
</section>
