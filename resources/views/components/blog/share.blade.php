@props(['url', 'title', 'label' => 'Share this article'])

@php($encodedUrl = urlencode($url))
@php($encodedTitle = urlencode($title))
<section class="blog-share-panel" aria-label="{{ $label }}">
    <strong>{{ $label }}</strong>
    <div class="blog-share-row">
        <button class="btn btn-sm" type="button" data-copy-text="{{ $url }}">Copy Link</button>
        <a class="btn btn-sm" href="https://wa.me/?text={{ $encodedTitle }}%20{{ $encodedUrl }}" target="_blank" rel="noopener">WhatsApp</a>
        <a class="btn btn-sm" href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener">Facebook</a>
        <a class="btn btn-sm" href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}" target="_blank" rel="noopener">X</a>
        <a class="btn btn-sm" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}" target="_blank" rel="noopener">LinkedIn</a>
        <a class="btn btn-sm" href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedTitle }}" target="_blank" rel="noopener">Telegram</a>
        <button class="btn btn-sm" type="button" data-native-share data-share-title="{{ $title }}" data-share-url="{{ $url }}">Share…</button>
    </div>
</section>
