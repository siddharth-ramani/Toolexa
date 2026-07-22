@extends('layouts.app')

@php
    $mode = $toolMeta['slug'];
    $sellerBasePath = rtrim(request()->getBaseUrl(), '/');
    $sellerAssetRoot = ($sellerBasePath === '' || str_ends_with($sellerBasePath, '/public'))
        ? $sellerBasePath.'/assets'
        : $sellerBasePath.'/public/assets';
@endphp

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ $toolMeta['category'] }} tool</span>
        <h1>{{ $toolMeta['name'] }}</h1>
        <p>{{ $toolMeta['desc'] }}</p>
    </section>

    <section class="tool-layout seller-tool crop-tool" data-seller-crop-tool data-seller-mode="{{ $mode }}">
        <div class="form-panel">
            <div class="mb-3">
                <label>Upload PDF</label>
                <div class="crop-dropzone" data-crop-dropzone tabindex="0">
                    <strong>Drag &amp; drop a PDF here</strong>
                    <span>or click to choose a file</span>
                    <input type="file" accept="application/pdf,.pdf" hidden data-crop-file-input>
                </div>
                <div class="crop-file-info" data-crop-file-info hidden>
                    <small>Page size: <span data-crop-page-size>&mdash;</span></small>
                    <small>Pages: <span data-crop-page-count>&mdash;</span></small>
                    <small>File size: <span data-crop-file-size>&mdash;</span></small>
                </div>
                <p class="tool-note">We crop the shipping label automatically. If it is not quite right, drag the box on the preview to adjust it.</p>
                <p class="crop-error" data-crop-error hidden></p>
            </div>

            <button class="btn btn-primary w-100" type="button" data-crop-export>Download Cropped Label</button>
            <button class="btn btn-secondary w-100 mt-2" type="button" data-crop-clear>Clear File</button>
            <p class="tool-note">PDFs are rendered and cropped entirely in your browser. Files are never uploaded or stored on Toolexa servers.</p>

            <button type="button" class="crop-advanced-toggle" data-crop-advanced-toggle aria-expanded="false">Show advanced controls</button>

            <div class="mb-3" data-crop-advanced hidden>
                <label>Marketplace Layout</label>
                <div class="seller-layout-grid" role="radiogroup" aria-label="Select label layout">
                    @foreach($toolMeta['label_layouts'] as $layout)
                        <label class="seller-layout-card @if($loop->first) is-selected @endif">
                            <input
                                type="radio"
                                name="seller_layout_{{ $toolMeta['slug'] }}"
                                value="{{ $layout['key'] }}"
                                data-crop-layout-option
                                @checked($loop->first)
                            >
                            <span>
                                <strong>{{ $layout['label'] }}</strong>
                                <small>{{ $layout['hint'] }}</small>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-3" data-crop-advanced hidden>
                <label>One-Click Crop Presets</label>
                <div class="crop-preset-row">
                    <button type="button" class="btn btn-secondary btn-sm" data-crop-preset="shipping_label">Crop Shipping Label</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-crop-preset="invoice">Crop Invoice</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-crop-preset="barcode_area">Crop Barcode Area</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-crop-preset="packing_slip">Crop Packing Slip</button>
                </div>
                <p class="tool-note">Presets are a starting point. Drag, resize or zoom the crop box to fine-tune it. If a preset is not available for the selected layout, adjust the box manually.</p>
            </div>

            <div class="mb-3" data-crop-advanced hidden>
                <label>Export Format</label>
                <div class="crop-format-row">
                    <label class="checkbox-row">
                        <input type="radio" name="crop_format_{{ $toolMeta['slug'] }}" value="pdf" data-crop-export-format checked>
                        <span>PDF &mdash; vector crop, applied to every page</span>
                    </label>
                    <label class="checkbox-row">
                        <input type="radio" name="crop_format_{{ $toolMeta['slug'] }}" value="png" data-crop-export-format>
                        <span>PNG &mdash; high resolution, current page only</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="result-box crop-result-box">
            <span class="eyebrow">Preview &amp; Crop</span>

            <div class="crop-card">
                <div class="crop-card-toolbar" data-crop-advanced hidden>
                    <div class="crop-tool-group">
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-pan-toggle title="Drag to pan the page">Pan</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-reset title="Reset crop box">Reset</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-undo title="Undo">Undo</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-redo title="Redo">Redo</button>
                    </div>
                    <div class="crop-tool-group">
                        <label class="checkbox-row crop-aspect-toggle">
                            <input type="checkbox" data-crop-aspect-lock>
                            <span>Lock ratio</span>
                        </label>
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-fit-width title="Fit page to width">Fit Width</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-fit-height title="Fit page to height">Fit Height</button>
                    </div>
                </div>

                <div class="crop-stage" data-crop-stage>
                    <div class="crop-canvas-wrap" data-crop-canvas-wrap>
                        <canvas data-crop-pdf-canvas hidden></canvas>
                        <div class="crop-selection" data-crop-selection hidden>
                            <span class="crop-handle" data-crop-handle="nw"></span>
                            <span class="crop-handle" data-crop-handle="n"></span>
                            <span class="crop-handle" data-crop-handle="ne"></span>
                            <span class="crop-handle" data-crop-handle="e"></span>
                            <span class="crop-handle" data-crop-handle="se"></span>
                            <span class="crop-handle" data-crop-handle="s"></span>
                            <span class="crop-handle" data-crop-handle="sw"></span>
                            <span class="crop-handle" data-crop-handle="w"></span>
                        </div>
                    </div>
                    <div class="crop-empty" data-crop-empty>
                        <span>Upload a PDF to see the live preview and crop box.</span>
                    </div>
                </div>

                <div class="crop-card-footer" data-crop-advanced hidden>
                    <div class="crop-page-nav">
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-prev-page>&larr; Prev</button>
                        <span data-crop-page-indicator>Page 0 of 0</span>
                        <button type="button" class="btn btn-sm btn-secondary" data-crop-next-page>Next &rarr;</button>
                    </div>
                    <div class="crop-zoom-control">
                        <span>Zoom</span>
                        <input type="range" min="0.25" max="4" step="0.05" value="1" data-crop-zoom-range>
                        <span data-crop-zoom-value>100%</span>
                    </div>
                </div>
            </div>

            <div class="result-actions">
                <button class="btn btn-success disabled" type="button" data-crop-download>Download Again</button>
                <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
                <span class="copy-status" data-crop-status></span>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js" defer></script>
    <script src="{{ $sellerAssetRoot }}/js/seller-crop-engine.min.js?v={{ filemtime(public_path('assets/js/seller-crop-engine.min.js')) }}" defer></script>
    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Seller tools ad'])
@endsection
