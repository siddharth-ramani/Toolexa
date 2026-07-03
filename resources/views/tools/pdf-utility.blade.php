@extends('layouts.app')

@php
    $mode = $toolMeta['slug'];
    $isImageToPdf = $mode === 'image-to-pdf-converter';
    $isMerger = $mode === 'pdf-merger';
@endphp

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ $toolMeta['category'] }} tool</span>
        <h1>{{ $toolMeta['name'] }}</h1>
        <p>{{ $toolMeta['desc'] }}</p>
    </section>

    <section class="tool-layout pdf-tool" data-pdf-tool data-pdf-mode="{{ $mode }}">
        <div class="form-panel">
            <div class="mb-3">
                <label for="pdfInput">
                    @if($isImageToPdf)
                        Choose Images
                    @elseif($isMerger)
                        Choose PDF Files
                    @else
                        Choose PDF
                    @endif
                </label>
                <input
                    id="pdfInput"
                    type="file"
                    class="form-control"
                    @if($isImageToPdf)
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                    @else
                        accept="application/pdf"
                        @if($isMerger) multiple @endif
                    @endif
                    data-pdf-input
                >
            </div>

            <button class="btn btn-primary w-100" type="button" data-pdf-process>
                @if($isImageToPdf)
                    Convert to PDF
                @elseif($isMerger)
                    Merge PDF Files
                @elseif($mode === 'pdf-page-counter')
                    Count Pages
                @elseif($mode === 'pdf-metadata-viewer')
                    View Metadata
                @else
                    Check PDF
                @endif
            </button>
            <button class="btn btn-secondary w-100 mt-2" type="button" data-pdf-clear>Clear All</button>
            <p class="tool-note">Files are processed locally in your browser and are not uploaded or stored on the server.</p>
        </div>

        <div class="result-box">
            <span class="eyebrow">Preview</span>
            <div class="browser-result-panel" data-pdf-result>
                <span>Select a file to preview details.</span>
            </div>

            <div class="pdf-file-list" data-pdf-list></div>

            <textarea id="pdfCopySource-{{ $toolMeta['slug'] }}" class="copy-source" readonly data-pdf-copy-source></textarea>

            <div class="result-actions">
                <button class="btn btn-primary" type="button" data-copy-target="pdfCopySource-{{ $toolMeta['slug'] }}">Copy Result</button>
                <button class="btn btn-success disabled" type="button" data-pdf-download>Download PDF</button>
                <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
                <span class="copy-status" data-pdf-status data-copy-status></span>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js" defer></script>
    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'PDF tools ad'])
@endsection
