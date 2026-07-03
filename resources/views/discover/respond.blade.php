@extends('layouts.app')

@section('title', 'Describe '.$entry['name'].' in Three Words | Toolexa Discover')
@section('description', 'Choose three words anonymously and help '.$entry['name'].' discover how friends see them.')

@section('content')
    <section class="tool-hero discover-hero discover-theme-{{ $entry['theme'] ?? 'light' }}">
        <div class="discover-profile">
            @if(!empty($entry['photo']))
                <img src="{{ route('discover.photo', $entry['id']) }}" alt="{{ $entry['name'] }} profile photo" loading="lazy" decoding="async">
            @else
                <span>{{ mb_strtoupper(mb_substr($entry['name'], 0, 1)) }}</span>
            @endif
        </div>
        <span class="eyebrow">Anonymous response</span>
        <h1>{{ $feature->promptFor($entry['name']) }}</h1>
        <p>Select exactly three words. Your name is not required.</p>
    </section>

    <section class="form-panel discover-panel" data-discover-response>
        <form method="POST" action="{{ route('discover.submit', $entry['id']) }}">
            @csrf

            <div class="discover-choice-head">
                <label>Choose three words</label>
                <span data-discover-count>0 / 3 selected</span>
            </div>

            <div class="discover-chip-grid" role="group" aria-label="Words that describe {{ $entry['name'] }}">
                @foreach($feature->words as $word)
                    @php($inputId = 'word-'.\Illuminate\Support\Str::slug($word))
                    <label class="discover-chip" for="{{ $inputId }}">
                        <input id="{{ $inputId }}" type="checkbox" name="words[]" value="{{ $word }}" @checked(in_array($word, old('words', []), true))>
                        <span>{{ $word }}</span>
                    </label>
                @endforeach
            </div>

            <div class="mb-3">
                <label>Emoji <span class="text-muted">(optional)</span></label>
                <div class="discover-emoji-row" role="radiogroup" aria-label="Optional emoji">
                    @foreach($feature->emojis as $emoji)
                        @php($emojiId = 'emoji-'.md5($emoji))
                        <label class="discover-emoji" for="{{ $emojiId }}">
                            <input id="{{ $emojiId }}" type="radio" name="emoji" value="{{ $emoji }}" @checked(old('emoji') === $emoji)>
                            <span>{{ $emoji }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="discover-message">Anonymous Message <span class="text-muted">(optional)</span></label>
                <textarea id="discover-message" class="form-control" name="message" maxlength="120" rows="3" placeholder="Write something short...">{{ old('message') }}</textarea>
            </div>

            <button class="btn btn-primary w-100" type="submit" data-discover-submit disabled>Submit</button>
        </form>
    </section>
@endsection
