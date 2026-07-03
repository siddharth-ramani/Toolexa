@extends('layouts.app')

@php
    $mode = $toolMeta['slug'];
@endphp

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ $toolMeta['category'] }} tool</span>
        <h1>{{ $toolMeta['name'] }}</h1>
        <p>{{ $toolMeta['desc'] }}</p>
    </section>

    <section class="tool-layout seller-tool" data-seller-tool data-seller-mode="{{ $mode }}">
        <div class="form-panel">
            <div class="mb-3">
                <label>Select Label Type</label>
                <div class="seller-layout-grid" role="radiogroup" aria-label="Select label type">
                    @foreach($toolMeta['label_layouts'] as $layoutKey => $layoutLabel)
                        <label class="seller-layout-card @if($loop->first) is-selected @endif">
                            <input
                                type="radio"
                                name="seller_layout_{{ $toolMeta['slug'] }}"
                                value="{{ $layoutKey }}"
                                data-seller-layout-option
                                @checked($loop->first)
                            >
                            <span>
                                <strong>{{ $layoutLabel }}</strong>
                                <small>
                                    @if($layoutKey === 'auto')
                                        Recommended. Let Toolexa pick the closest Amazon layout.
                                    @elseif(str_contains($layoutKey, 'top'))
                                        Use when the shipping label appears near the top of the A4 page.
                                    @elseif(str_contains($layoutKey, 'center'))
                                        Use when the shipping label appears around the middle of the page.
                                    @elseif(str_contains($layoutKey, 'block'))
                                        Use for marketplace shipping block style labels.
                                    @else
                                        Best for the common A4 marketplace label export.
                                    @endif
                                </small>
                            </span>
                        </label>
                    @endforeach
                </div>
                <input type="hidden" value="{{ array_key_first($toolMeta['label_layouts']) }}" data-seller-layout>
            </div>

            <div class="mb-3">
                <label for="sellerPdfInput">Upload PDF</label>
                <input id="sellerPdfInput" type="file" class="form-control" accept="application/pdf" data-seller-input>
            </div>

            <button class="btn btn-primary w-100" type="button" data-seller-process>Process Label</button>
            <button class="btn btn-secondary w-100 mt-2" type="button" data-seller-clear>Clear File</button>
            <p class="tool-note">Label PDFs are processed locally in your browser using predefined marketplace crop layouts. Files are not uploaded or stored.</p>
        </div>

        <div class="result-box">
            <span class="eyebrow">Output</span>
            <div class="browser-result-panel" data-seller-result>
                <span>Upload a PDF and click Process Label.</span>
            </div>

            <textarea id="sellerCopySource-{{ $toolMeta['slug'] }}" class="copy-source" readonly data-seller-copy-source></textarea>

            <div class="result-actions">
                <button class="btn btn-primary" type="button" data-copy-target="sellerCopySource-{{ $toolMeta['slug'] }}">Copy Result</button>
                <button class="btn btn-success disabled" type="button" data-seller-download>Download PDF</button>
                <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
                <span class="copy-status" data-seller-status data-copy-status></span>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js" defer></script>
    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Seller tools ad'])
@endsection
