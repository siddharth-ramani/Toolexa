<div class="tool-quality-content">
    <x-tool-quality.steps :tool="$toolMeta" :steps="$toolQuality['steps']" :note="$toolQuality['steps_note'] ?? null" />
    <x-tool-quality.examples :tool="$toolMeta" :examples="$toolQuality['examples']" />

    @if($toolQuality['formula'])
        <x-tool-quality.formula :formula="$toolQuality['formula']" />
    @endif

    <section class="info-panel tool-quality-section" aria-labelledby="detailed-explanation-heading">
        <span class="eyebrow">In depth</span>
        <h2 id="detailed-explanation-heading">How {{ $toolMeta['name'] }} Works</h2>
        @foreach($toolQuality['detailed_explanation'] as $paragraph)<p>{{ $paragraph }}</p>@endforeach
    </section>

    <x-tool-quality.list-section :title="'Advantages of Using '.$toolMeta['name']" eyebrow="Benefits" :items="$toolQuality['advantages']" id="tool-advantages" />
    <x-tool-quality.list-section title="Limitations to Understand" eyebrow="Important context" :items="$toolQuality['limitations']" id="tool-limitations" />
    <x-tool-quality.list-section title="Common Mistakes" eyebrow="Avoid these issues" :items="$toolQuality['mistakes']" id="tool-mistakes" ordered />
    <x-tool-quality.list-section title="Best Practices" eyebrow="Better results" :items="$toolQuality['best_practices']" id="tool-best-practices" />

    <section class="info-panel faq-panel tool-quality-section" aria-labelledby="tool-quality-faq-heading">
        <span class="eyebrow">Questions answered</span>
        <h2 id="tool-quality-faq-heading">{{ $toolMeta['name'] }} FAQs</h2>
        @foreach($toolQuality['faqs'] as $faq)
            <details><summary>{{ $faq['question'] }}</summary><p>{{ $faq['answer'] }}</p></details>
        @endforeach
    </section>

    <x-related-tools :tools="$relatedTools ?? []" />
    <x-related-articles :articles="$relatedArticles ?? []" heading="Learn more about this tool" />
    <x-tool-quality.comparisons :comparisons="$toolQuality['comparisons']" />
    <x-tool-quality.references :references="$toolQuality['references']" />

    <section class="info-panel tool-quality-verification" aria-label="Page verification details">
        <span class="eyebrow">Reviewed information</span>
        <h2>Page Details</h2>
        <x-editorial.metadata-row :metadata="$editorialMeta" type="tool" :category="$toolMeta['category']" />
    </section>

    <x-tool-quality.feedback :slug="$toolMeta['slug']" />

    <section class="tool-actions" data-tool-actions>
        <button class="btn btn-primary" type="button" data-copy-url>Copy Link</button>
        <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
        <a class="btn" href="{{ route('search') }}">Explore More Free Tools</a>
        <span class="copy-status" data-copy-status></span>
    </section>
</div>
