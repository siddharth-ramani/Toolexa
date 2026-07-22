@props(['eyebrow', 'title', 'id', 'description' => null, 'url' => null, 'linkLabel' => null])

<div class="section-head premium-section-head">
    <div>
        <span class="eyebrow">{{ $eyebrow }}</span>
        <h2 id="{{ $id }}">{{ $title }}</h2>
        @if($description)<p>{{ $description }}</p>@endif
    </div>
    @if($url)<a class="btn btn-sm" href="{{ $url }}">{{ $linkLabel }}</a>@endif
</div>
