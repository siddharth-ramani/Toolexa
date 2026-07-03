@extends('layouts.app')

@php
    $currentText = old('text', $validated['text'] ?? '');
    $copyTarget = 'text-output-'.$toolMeta['slug'];
    $shareText = '';

    if (isset($result)) {
        $shareText = $result['type'] === 'text'
            ? $result['text']
            : collect($result['items'])->map(fn ($value, $label) => $label.': '.$value)->implode("\n");
    }
@endphp

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ $toolMeta['category'] }} tool</span>
        <h1>{{ $toolMeta['name'] }}</h1>
        <p>{{ $toolMeta['desc'] }}</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ url('tools/'.$toolMeta['slug']) }}">
                @csrf

                @unless($toolMeta['slug'] === 'lorem-ipsum-generator')
                    <div class="mb-3">
                        <label for="text">Enter Text</label>
                        <textarea id="text" name="text" class="form-control" rows="8" maxlength="20000" required>{{ $currentText }}</textarea>
                    </div>
                @endunless

                @if($toolMeta['slug'] === 'url-encoder-decoder')
                    <div class="mb-3">
                        <label for="operation">Operation</label>
                        <select id="operation" name="operation" class="form-control" required>
                            @foreach(['encode' => 'Encode URL', 'decode' => 'Decode URL'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('operation', $validated['operation'] ?? 'encode') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($toolMeta['slug'] === 'remove-duplicate-lines')
                    <label class="checkbox-row">
                        <input type="checkbox" name="case_sensitive" value="1" @checked(old('case_sensitive', ! empty($validated['case_sensitive'] ?? false)))>
                        <span>Case-sensitive matching</span>
                    </label>
                @endif

                @if($toolMeta['slug'] === 'remove-extra-spaces')
                    <label class="checkbox-row">
                        <input type="checkbox" name="remove_empty_lines" value="1" @checked(old('remove_empty_lines', ! empty($validated['remove_empty_lines'] ?? false)))>
                        <span>Remove empty lines</span>
                    </label>
                @endif

                @if($toolMeta['slug'] === 'text-repeater')
                    <div class="mb-3">
                        <label for="times">Repeat Count</label>
                        <input id="times" type="number" name="times" class="form-control" min="1" max="500" value="{{ old('times', $validated['times'] ?? 3) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="separator">Separator</label>
                        <select id="separator" name="separator" class="form-control" required>
                            @foreach(['space' => 'Space', 'new_line' => 'New Line', 'comma' => 'Comma', 'custom' => 'Custom'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('separator', $validated['separator'] ?? 'new_line') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="custom_separator">Custom Separator</label>
                        <input id="custom_separator" type="text" name="custom_separator" class="form-control" maxlength="100" value="{{ old('custom_separator', $validated['custom_separator'] ?? '') }}">
                    </div>
                @endif

                @if($toolMeta['slug'] === 'lorem-ipsum-generator')
                    <div class="mb-3">
                        <label for="lorem_type">Generate</label>
                        <select id="lorem_type" name="lorem_type" class="form-control" required>
                            @foreach(['paragraphs' => 'Paragraphs', 'sentences' => 'Sentences', 'words' => 'Words'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('lorem_type', $validated['lorem_type'] ?? 'paragraphs') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity">Quantity</label>
                        <input id="quantity" type="number" name="quantity" class="form-control" min="1" max="100" value="{{ old('quantity', $validated['quantity'] ?? 3) }}" required>
                    </div>
                @endif

                <button class="btn btn-primary w-100" type="submit">
                    @if(in_array($toolMeta['slug'], ['word-counter', 'character-counter'], true))
                        Count Text
                    @elseif(in_array($toolMeta['slug'], ['base64-encoder', 'base64-decoder', 'url-encoder-decoder', 'md5-hash-generator'], true))
                        Convert Instantly
                    @elseif($toolMeta['slug'] === 'lorem-ipsum-generator')
                        Generate Text
                    @else
                        Process Text
                    @endif
                </button>
                <button class="btn btn-secondary w-100 mt-2" type="reset" data-clear-tool>Clear</button>
            </form>
        </div>

        @isset($result)
            <div class="result-box">
                <span class="eyebrow">Result</span>

                @if($result['type'] === 'stats')
                    <div class="finance-result-grid">
                        @foreach($result['items'] as $label => $value)
                            <div class="finance-result-item">
                                <span>{{ $label }}</span>
                                <strong>{{ is_numeric($value) ? number_format($value) : $value }}</strong>
                            </div>
                        @endforeach
                    </div>
                    <textarea id="{{ $copyTarget }}" class="copy-source" readonly>@foreach($result['items'] as $label => $value){{ $label }}: {{ $value }}
@endforeach</textarea>
                @else
                    @if(! empty($result['summary']))
                        <div class="finance-result-grid">
                            @foreach($result['summary'] as $label => $value)
                                <div class="finance-result-item">
                                    <span>{{ $label }}</span>
                                    <strong>{{ is_numeric($value) ? number_format($value) : $value }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label for="{{ $copyTarget }}">{{ $result['label'] }}</label>
                    <textarea id="{{ $copyTarget }}" class="form-control text-output" rows="8" readonly>{{ $result['text'] }}</textarea>
                @endif

                <div class="result-actions">
                    <button class="btn btn-primary" type="button" data-copy-target="{{ $copyTarget }}">Copy Result</button>
                    <a class="btn btn-success" target="_blank" rel="noopener" href="https://wa.me/?text={{ rawurlencode($shareText) }}">Share on WhatsApp</a>
                    <span class="copy-status" data-copy-status></span>
                </div>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => $toolMeta['category'].' ad'])
@endsection
