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

    <section class="tool-layout browser-tool" data-browser-tool data-browser-mode="{{ $mode }}">
        <div class="form-panel">
            @if($mode === 'uuid-generator')
                <div class="mb-3">
                    <label for="uuidCount">How many UUIDs?</label>
                    <input id="uuidCount" class="form-control" type="number" min="1" max="100" value="5" data-uuid-count>
                </div>
                <button class="btn btn-primary w-100" type="button" data-browser-process>Generate UUIDs</button>
            @elseif($mode === 'random-number-generator')
                <div class="image-control-grid">
                    <div class="mb-3">
                        <label for="numberMin">Minimum</label>
                        <input id="numberMin" class="form-control" type="number" value="1" data-number-min>
                    </div>
                    <div class="mb-3">
                        <label for="numberMax">Maximum</label>
                        <input id="numberMax" class="form-control" type="number" value="100" data-number-max>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="numberCount">How many numbers?</label>
                    <input id="numberCount" class="form-control" type="number" min="1" max="100" value="10" data-number-count>
                </div>
                <label class="checkbox-row">
                    <input type="checkbox" data-number-duplicates checked>
                    <span>Allow duplicates</span>
                </label>
                <button class="btn btn-primary w-100" type="button" data-browser-process>Generate Numbers</button>
            @elseif($mode === 'random-string-generator')
                <div class="mb-3">
                    <label for="stringLength">String Length</label>
                    <input id="stringLength" class="form-control" type="number" min="1" max="1000" value="16" data-string-length>
                </div>
                <div class="option-grid">
                    <label class="checkbox-row"><input type="checkbox" data-string-upper checked><span>Uppercase</span></label>
                    <label class="checkbox-row"><input type="checkbox" data-string-lower checked><span>Lowercase</span></label>
                    <label class="checkbox-row"><input type="checkbox" data-string-numbers checked><span>Numbers</span></label>
                    <label class="checkbox-row"><input type="checkbox" data-string-symbols><span>Symbols</span></label>
                    <label class="checkbox-row option-wide"><input type="checkbox" data-string-similar><span>Exclude similar characters</span></label>
                </div>
                <button class="btn btn-primary w-100" type="button" data-browser-process>Generate String</button>
            @elseif($mode === 'uuid-validator')
                <div class="mb-3">
                    <label for="uuidInput">Enter UUID</label>
                    <input id="uuidInput" class="form-control" type="text" placeholder="xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx" data-uuid-input>
                </div>
                <button class="btn btn-primary w-100" type="button" data-browser-process>Validate UUID</button>
            @else
                <div class="mb-3">
                    <label for="conversionMode">Conversion</label>
                    <select id="conversionMode" class="form-control" data-binary-mode>
                        <option value="binary-to-decimal">Binary to Decimal</option>
                        <option value="decimal-to-binary">Decimal to Binary</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="binaryInput">Value</label>
                    <input id="binaryInput" class="form-control" type="text" placeholder="101010" data-binary-input>
                </div>
                <button class="btn btn-primary w-100" type="button" data-browser-process>Convert Instantly</button>
            @endif

            <button class="btn btn-secondary w-100 mt-2" type="button" data-browser-clear>Clear</button>
            <p class="tool-note">Processing runs locally in your browser. Toolexa does not store your input.</p>
        </div>

        <div class="result-box">
            <span class="eyebrow">Result</span>
            <div class="browser-result-panel" data-browser-result>
                <span>Result will appear here.</span>
            </div>

            <textarea id="browserCopySource-{{ $toolMeta['slug'] }}" class="copy-source" readonly data-browser-copy-source></textarea>

            <div class="result-actions">
                <button class="btn btn-primary" type="button" data-copy-target="browserCopySource-{{ $toolMeta['slug'] }}">Copy Result</button>
                @if($mode === 'uuid-generator')
                    <button class="btn btn-success" type="button" data-browser-copy-all>Copy All UUIDs</button>
                    <button class="btn btn-secondary" type="button" data-browser-download>Download TXT</button>
                @endif
                <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
                <span class="copy-status" data-copy-status></span>
            </div>
        </div>
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => $toolMeta['category'].' ad'])
@endsection
