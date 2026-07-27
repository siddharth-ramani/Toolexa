@props(['faqs', 'title'])

<section class="info-panel faq-panel" aria-labelledby="comparison-faq-heading">
    <span class="eyebrow">Questions answered</span>
    <h2 id="comparison-faq-heading">{{ $title }} FAQs</h2>
    @foreach($faqs as $faq)
        <details><summary>{{ $faq['question'] }}</summary><p>{{ $faq['answer'] }}</p></details>
    @endforeach
</section>
