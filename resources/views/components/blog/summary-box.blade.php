@props(['items'])

<section class="blog-summary info-panel" aria-labelledby="quick-summary-heading">
    <span class="eyebrow">Quick Summary</span>
    <h2 id="quick-summary-heading">Key takeaways</h2>
    <ul>
        @foreach($items as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
</section>
