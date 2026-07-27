@props(['block'])

<aside class="blog-info-block blog-info-{{ $block['type'] }}" aria-label="{{ $block['label'] }}">
    <strong>{{ $block['label'] }}</strong>
    <p>{{ $block['text'] }}</p>
</aside>
