<section class="info-panel tool-introduction">
    <span class="eyebrow">Introduction</span>
    <h2>About {{ $toolMeta['name'] }}</h2>
    <p>{{ $toolMeta['introduction'] }}</p>
</section>

<section class="info-panel">
    <span class="eyebrow">How to use</span>
    <h2>How to use {{ $toolMeta['name'] }}?</h2>
    <ol class="step-list">
        @foreach($toolMeta['how_to'] as $step)
            <li>{{ $step }}</li>
        @endforeach
    </ol>
</section>

@if(! empty($toolMeta['formula']))
    <section class="info-panel">
        <span class="eyebrow">Formula</span>
        <h2>{{ $toolMeta['formula']['title'] }}</h2>
        <ul class="formula-list">
            @foreach($toolMeta['formula']['items'] as $formula)
                <li>{{ $formula }}</li>
            @endforeach
        </ul>
        <p>{{ $toolMeta['formula']['explanation'] }}</p>
        <article class="example-box">
            <h3>Example</h3>
            <p>{{ $toolMeta['formula']['example'] }}</p>
        </article>
    </section>
@endif

<section class="info-panel">
    <span class="eyebrow">Features</span>
    <h2>{{ $toolMeta['name'] }} Features</h2>
    <ul class="feature-list">
        @foreach($toolMeta['features'] as $feature)
            <li>{{ $feature }}</li>
        @endforeach
    </ul>
</section>

<section class="info-panel faq-panel">
    <span class="eyebrow">FAQ</span>
    <h2>{{ $toolMeta['name'] }} FAQs</h2>
    @foreach($toolMeta['faq'] as $faq)
        <details>
            <summary>{{ $faq['q'] }}</summary>
            <p>{{ $faq['a'] }}</p>
        </details>
    @endforeach
</section>

@if(! empty($relatedTools))
    <section class="info-panel">
        <span class="eyebrow">Related tools</span>
        <h2>Related Tools</h2>
        <div class="mini-tool-grid">
            @foreach($relatedTools as $tool)
                @include('partials.tool-card', ['tool' => $tool])
            @endforeach
        </div>
    </section>
@endif

<section class="tool-actions" data-tool-actions>
    <button class="btn btn-primary" type="button" data-copy-url>Copy Link</button>
    <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
    <span class="copy-status" data-copy-status></span>
</section>
