@props(['title', 'faqs'])

<section class="info-panel faq-panel" aria-labelledby="authority-faq-heading">
    <span class="eyebrow">Frequently Asked Questions</span>
    <h2 id="authority-faq-heading">{{ $title }} FAQs</h2>
    @foreach($faqs as $faq)<details><summary>{{ $faq['question'] }}</summary><p>{{ $faq['answer'] }}</p></details>@endforeach
</section>
