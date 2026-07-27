@props(['title', 'eyebrow', 'items', 'id', 'ordered' => false])
<section class="info-panel tool-quality-section" aria-labelledby="{{ $id }}-heading">
    <span class="eyebrow">{{ $eyebrow }}</span>
    <h2 id="{{ $id }}-heading">{{ $title }}</h2>
    @if($ordered)<ol class="quality-list">@else<ul class="quality-list">@endif
        @foreach($items as $item)<li>{{ $item }}</li>@endforeach
    @if($ordered)</ol>@else</ul>@endif
</section>
