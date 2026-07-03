@extends('layouts.app')

@section('title', 'Share Your Discover Link - '.$feature->name)
@section('description', 'Share your Toolexa Discover link with friends.')

@section('content')
    @php
        $fullShareText = trim($shareCaption)."\n".$shareUrl;
        $instagramCaption = trim($shareCaption)."\n\nLink: ".$shareUrl;
    @endphp

    <section class="tool-hero discover-hero discover-theme-{{ $entry['theme'] ?? 'light' }}">
        <span class="eyebrow">Your link is ready</span>
        <h1>Share this with friends</h1>
        <p>Use the ready-made post below so friends instantly understand what to do.</p>
    </section>

    <section class="result-box discover-share-box">
        <div class="discover-share-preview">
            <span class="eyebrow">Ready post</span>
            <h2>{{ $shareTitle }}</h2>
            <p>{!! nl2br(e(trim($shareCaption))) !!}</p>
            <code>{{ $shareUrl }}</code>
            <div class="result-actions">
                <button class="btn btn-primary" type="button" data-copy-text="{{ $fullShareText }}">Copy Full Post</button>
                <button class="btn" type="button" data-copy-text="{{ $instagramCaption }}">Copy Instagram Caption</button>
            </div>
        </div>

        <label for="discover-share-url">Public response link</label>
        <div class="discover-copy-row">
            <input id="discover-share-url" class="form-control" type="text" readonly value="{{ $shareUrl }}">
            <button class="btn btn-primary" type="button" data-copy-target="discover-share-url">Copy Link</button>
            <span class="copy-status" data-copy-status></span>
        </div>

        <div class="discover-share-grid" data-discover-share data-share-url="{{ $shareUrl }}" data-share-title="{{ $shareTitle }}" data-share-text="{{ trim($shareCaption) }}">
            <button class="btn btn-primary" type="button" data-native-share>Share</button>
            <a class="btn btn-success" href="https://wa.me/?text={{ rawurlencode($fullShareText) }}" target="_blank" rel="noopener">WhatsApp</a>
            <button class="btn" type="button" data-copy-text="{{ $instagramCaption }}">Instagram</button>
            <a class="btn" href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($shareUrl) }}" target="_blank" rel="noopener">Facebook</a>
            <a class="btn" href="https://t.me/share/url?url={{ rawurlencode($shareUrl) }}&text={{ rawurlencode(trim($shareCaption)) }}" target="_blank" rel="noopener">Telegram</a>
            <a class="btn" href="https://twitter.com/intent/tweet?text={{ rawurlencode(trim($shareCaption)) }}&url={{ rawurlencode($shareUrl) }}" target="_blank" rel="noopener">X</a>
            <a class="btn" href="https://www.reddit.com/submit?url={{ rawurlencode($shareUrl) }}&title={{ rawurlencode($shareTitle) }}" target="_blank" rel="noopener">Reddit</a>
        </div>

        <div class="result-actions">
            <a class="btn btn-primary" href="{{ $resultsUrl }}">View Results Dashboard</a>
        </div>
    </section>
@endsection
