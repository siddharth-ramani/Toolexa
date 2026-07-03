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

    <section class="tool-layout image-tool" data-image-tool data-image-mode="{{ $mode }}">
        <div class="form-panel">
            <div class="mb-3">
                <label for="imageInput">Choose Image</label>
                <input id="imageInput" type="file" class="form-control" accept="image/jpeg,image/png,image/webp" data-image-input>
            </div>

            @if($mode === 'image-resizer')
                <div class="image-control-grid">
                    <div class="mb-3">
                        <label for="resizeWidth">Width</label>
                        <input id="resizeWidth" type="number" class="form-control" min="1" data-resize-width>
                    </div>
                    <div class="mb-3">
                        <label for="resizeHeight">Height</label>
                        <input id="resizeHeight" type="number" class="form-control" min="1" data-resize-height>
                    </div>
                </div>
                <label class="checkbox-row">
                    <input type="checkbox" data-maintain-ratio checked>
                    <span>Maintain aspect ratio</span>
                </label>
            @endif

            @if($mode === 'image-compressor')
                <div class="mb-3">
                    <label for="qualityRange">Compression Quality: <span data-quality-value>80</span>%</label>
                    <input id="qualityRange" type="range" class="form-control" min="10" max="100" value="80" data-quality-range>
                </div>
            @endif

            @if($mode === 'png-to-jpg-converter')
                <div class="mb-3">
                    <label for="backgroundColor">Background Color</label>
                    <input id="backgroundColor" type="color" class="form-control color-input" value="#ffffff" data-background-color>
                </div>
            @endif

            @if($mode === 'image-cropper')
                <div class="mb-3">
                    <label for="aspectRatio">Aspect Ratio</label>
                    <select id="aspectRatio" class="form-control" data-aspect-ratio>
                        <option value="free">Free</option>
                        <option value="1">1:1</option>
                        <option value="1.3333333333">4:3</option>
                        <option value="1.7777777778">16:9</option>
                    </select>
                </div>
            @endif

            <button class="btn btn-primary w-100" type="button" data-image-process>
                @if($mode === 'image-resizer')
                    Resize Image
                @elseif($mode === 'image-compressor')
                    Compress Image
                @elseif($mode === 'jpg-to-png-converter')
                    Convert to PNG
                @elseif($mode === 'png-to-jpg-converter')
                    Convert to JPG
                @else
                    Crop Image
                @endif
            </button>
            <button class="btn btn-secondary w-100 mt-2" type="button" data-image-clear>Clear Image</button>
            <p class="tool-note">Images are processed locally in your browser using canvas and are not uploaded to the server.</p>
        </div>

        <div class="result-box image-result-box">
            <span class="eyebrow">Preview</span>

            <div class="image-preview-grid">
                <div class="image-preview-panel">
                    <strong>Original</strong>
                    <div class="image-preview-frame">
                        <img alt="Original preview" data-original-preview hidden>
                        <canvas data-crop-canvas hidden></canvas>
                        <span data-empty-original>Select an image to preview it.</span>
                    </div>
                    <small data-original-meta></small>
                </div>

                <div class="image-preview-panel">
                    <strong>Output</strong>
                    <div class="image-preview-frame">
                        <img alt="Output preview" data-output-preview hidden>
                        <span data-empty-output>Processed image will appear here.</span>
                    </div>
                    <small data-output-meta></small>
                </div>
            </div>

            <div class="result-actions">
                <a class="btn btn-primary disabled" href="#" download data-image-download>Download Image</a>
                <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
                <span class="copy-status" data-image-status></span>
            </div>
        </div>
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Image tools ad'])
@endsection
