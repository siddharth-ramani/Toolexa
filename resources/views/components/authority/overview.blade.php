@props(['title', 'paragraphs'])

<section class="info-panel authority-overview" aria-labelledby="authority-overview-heading">
    <span class="eyebrow">Quick Overview</span>
    <h2 id="authority-overview-heading">Understanding {{ $title }}</h2>
    @foreach($paragraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
</section>
