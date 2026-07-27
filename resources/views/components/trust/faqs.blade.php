@props(['faqs'])

<section class="info-panel trust-faqs" aria-labelledby="trust-faq-heading">
    <span class="eyebrow">Questions answered</span>
    <h2 id="trust-faq-heading">Frequently Asked Questions</h2>
    @foreach($faqs as $faq)
        <details>
            <summary>{{ $faq['question'] }}</summary>
            <p>{{ $faq['answer'] }}</p>
        </details>
    @endforeach
</section>
