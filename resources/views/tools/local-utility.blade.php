@extends('layouts.app')

@php($mode = $toolMeta['slug'])

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ $toolMeta['category'] }} tool</span>
        <h1>{{ $toolMeta['name'] }}</h1>
        <p>{{ $toolMeta['desc'] }}</p>
    </section>

    <section class="tool-layout local-tool" data-local-tool data-local-mode="{{ $mode }}">
        <div class="form-panel">
            @if($mode === 'hex-rgb-hsl-color-converter')
                <div class="mb-3">
                    <label for="colorHex">HEX</label>
                    <input id="colorHex" class="form-control" type="text" value="#0f766e" placeholder="#0f766e" data-color-hex>
                </div>
                <div class="image-control-grid">
                    <div class="mb-3"><label for="colorR">R</label><input id="colorR" class="form-control" type="number" min="0" max="255" value="15" data-color-r></div>
                    <div class="mb-3"><label for="colorG">G</label><input id="colorG" class="form-control" type="number" min="0" max="255" value="118" data-color-g></div>
                    <div class="mb-3"><label for="colorB">B</label><input id="colorB" class="form-control" type="number" min="0" max="255" value="110" data-color-b></div>
                </div>
                <div class="image-control-grid">
                    <div class="mb-3"><label for="colorH">H</label><input id="colorH" class="form-control" type="number" min="0" max="360" value="176" data-color-h></div>
                    <div class="mb-3"><label for="colorS">S (%)</label><input id="colorS" class="form-control" type="number" min="0" max="100" value="77" data-color-s></div>
                    <div class="mb-3"><label for="colorL">L (%)</label><input id="colorL" class="form-control" type="number" min="0" max="100" value="26" data-color-l></div>
                </div>
                <div class="tool-actions compact-actions">
                    <button class="btn btn-primary" type="button" data-local-action="hex">HEX to RGB</button>
                    <button class="btn btn-secondary" type="button" data-local-action="rgb">RGB to HEX</button>
                    <button class="btn" type="button" data-local-action="rgb-hsl">RGB to HSL</button>
                    <button class="btn" type="button" data-local-action="hsl-rgb">HSL to RGB</button>
                </div>
            @elseif($mode === 'barcode-generator')
                <div class="mb-3">
                    <label for="barcodeType">Barcode Type</label>
                    <select id="barcodeType" class="form-control" data-barcode-type>
                        <option value="code128">Code128</option>
                        <option value="code39">Code39</option>
                        <option value="ean13">EAN-13</option>
                        <option value="upca">UPC-A</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="barcodeValue">Barcode Value</label>
                    <input id="barcodeValue" class="form-control" type="text" value="TOOLEXA123" maxlength="80" data-barcode-value>
                </div>
                <div class="tool-actions compact-actions">
                    <button class="btn btn-primary" type="button" data-local-action="barcode">Generate Barcode</button>
                    <button class="btn" type="button" data-local-print>Print Barcode</button>
                </div>
            @elseif($mode === 'image-to-base64-converter')
                <div class="mb-3">
                    <label for="imageBase64Input">Upload Image</label>
                    <input id="imageBase64Input" class="form-control" type="file" accept="image/png,image/jpeg,image/webp,image/gif" data-image-base64-input>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="image-base64">Convert to Base64</button>
            @elseif($mode === 'robots-txt-generator')
                <div class="mb-3">
                    <label for="robotsUserAgent">User-agent</label>
                    <input id="robotsUserAgent" class="form-control" type="text" value="*" data-robots-agent>
                </div>
                <div class="mb-3">
                    <label for="robotsAllow">Allow Rules</label>
                    <textarea id="robotsAllow" class="form-control code-textarea" rows="4" placeholder="/&#10;/assets/" data-robots-allow></textarea>
                </div>
                <div class="mb-3">
                    <label for="robotsDisallow">Disallow Rules</label>
                    <textarea id="robotsDisallow" class="form-control code-textarea" rows="4" placeholder="/admin/&#10;/private/" data-robots-disallow></textarea>
                </div>
                <div class="mb-3">
                    <label for="robotsSitemap">Sitemap URL</label>
                    <input id="robotsSitemap" class="form-control" type="url" placeholder="https://example.com/sitemap.xml" data-robots-sitemap>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="robots">Generate robots.txt</button>
            @elseif($mode === 'password-strength-checker')
                <div class="mb-3">
                    <label for="passwordStrengthInput">Password</label>
                    <input id="passwordStrengthInput" class="form-control" type="password" autocomplete="new-password" data-password-strength-input>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="password-strength">Check Strength</button>
            @elseif($mode === 'csv-to-json-converter')
                <div class="mb-3">
                    <label for="csvInput">CSV Text</label>
                    <textarea id="csvInput" class="form-control code-textarea" rows="10" placeholder="name,email&#10;Toolexa,hello@example.com" data-csv-input></textarea>
                </div>
                <div class="mb-3">
                    <label for="csvFile">Upload CSV file</label>
                    <input id="csvFile" class="form-control" type="file" accept=".csv,text/csv" data-csv-file>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="csv-json">Convert CSV to JSON</button>
            @elseif($mode === 'timestamp-converter')
                <div class="mb-3">
                    <label for="unixTimestamp">Unix Timestamp</label>
                    <input id="unixTimestamp" class="form-control" type="number" placeholder="1720000000" data-timestamp-unix>
                </div>
                <div class="mb-3">
                    <label for="humanDate">Human Date</label>
                    <input id="humanDate" class="form-control" type="datetime-local" data-timestamp-human>
                </div>
                <div class="tool-actions compact-actions">
                    <button class="btn btn-primary" type="button" data-local-action="timestamp-human">Unix to Date</button>
                    <button class="btn btn-secondary" type="button" data-local-action="timestamp-unix">Date to Unix</button>
                    <button class="btn" type="button" data-local-action="timestamp-current">Current Timestamp</button>
                </div>
            @elseif($mode === 'webp-to-png-converter')
                <div class="mb-3">
                    <label for="webpInput">Upload WebP image</label>
                    <input id="webpInput" class="form-control" type="file" accept="image/webp" multiple data-webp-input>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="webp-png">Convert to PNG</button>
            @elseif($mode === 'keyword-density-checker')
                <div class="mb-3">
                    <label for="keywordText">Text Content</label>
                    <textarea id="keywordText" class="form-control" rows="12" maxlength="100000" placeholder="Paste article, page copy or SEO content..." data-keyword-text></textarea>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="keyword-density">Analyze Keyword Density</button>
            @elseif(in_array($mode, ['ico-favicon-generator', 'favicon-generator'], true))
                <div class="mb-3">
                    <label for="faviconInput">Upload image</label>
                    <input id="faviconInput" class="form-control" type="file" accept="image/png,image/jpeg,image/webp" data-favicon-input>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="favicon">Generate Favicons</button>
            @elseif($mode === 'mortgage-calculator')
                <div class="image-control-grid">
                    <div class="mb-3"><label for="mortgageLoan">Loan Amount</label><input id="mortgageLoan" class="form-control" type="number" min="0" step="0.01" value="250000" data-mortgage-loan></div>
                    <div class="mb-3"><label for="mortgageRate">Interest Rate (%)</label><input id="mortgageRate" class="form-control" type="number" min="0" step="0.01" value="6.5" data-mortgage-rate></div>
                    <div class="mb-3"><label for="mortgageYears">Loan Term (years)</label><input id="mortgageYears" class="form-control" type="number" min="1" step="1" value="30" data-mortgage-years></div>
                </div>
                <div class="tool-actions compact-actions">
                    <button class="btn btn-primary" type="button" data-local-action="mortgage">Calculate Mortgage</button>
                    <button class="btn" type="button" data-local-print>Print Result</button>
                </div>
            @elseif($mode === 'url-slug-generator')
                <div class="mb-3">
                    <label for="slugText">Text</label>
                    <textarea id="slugText" class="form-control" rows="8" maxlength="20000" placeholder="Enter page title or heading..." data-slug-text></textarea>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="slug">Generate Slug</button>
            @elseif($mode === 'color-picker-from-image')
                <div class="mb-3">
                    <label for="colorPickerImage">Upload image</label>
                    <input id="colorPickerImage" class="form-control" type="file" accept="image/png,image/jpeg,image/webp" data-picker-input>
                </div>
                <button class="btn btn-primary w-100" type="button" data-local-action="palette">Download Palette</button>
            @else
                <div class="image-control-grid">
                    <div class="mb-3">
                        <label for="vatAmount">Amount</label>
                        <input id="vatAmount" class="form-control" type="number" min="0" step="0.01" value="1000" data-vat-amount>
                    </div>
                    <div class="mb-3">
                        <label for="vatRate">VAT Rate (%)</label>
                        <input id="vatRate" class="form-control" type="number" min="0" step="0.01" value="20" data-vat-rate>
                    </div>
                </div>
                <div class="tool-actions compact-actions">
                    <button class="btn btn-primary" type="button" data-local-action="vat-add">Add VAT</button>
                    <button class="btn btn-secondary" type="button" data-local-action="vat-remove">Remove VAT</button>
                </div>
            @endif

            <button class="btn btn-secondary w-100 mt-2" type="button" data-local-clear>Clear</button>
            <p class="tool-note">Processing runs locally in your browser. Toolexa does not upload or permanently store your input.</p>
        </div>

        <div class="result-box">
            <span class="eyebrow">Result</span>
            <div class="local-preview" data-local-preview>
                @if($mode === 'hex-rgb-hsl-color-converter')
                    <div class="color-preview" data-color-preview></div>
                @elseif($mode === 'barcode-generator')
                    <div class="barcode-preview" data-barcode-preview></div>
                @elseif($mode === 'image-to-base64-converter')
                    <img class="image-output-preview" alt="Uploaded image preview" hidden data-image-base64-preview>
                @elseif($mode === 'password-strength-checker')
                    <div class="strength-meter" data-strength-meter><span></span></div>
                @elseif($mode === 'webp-to-png-converter')
                    <div class="image-preview-grid" data-webp-preview></div>
                @elseif(in_array($mode, ['ico-favicon-generator', 'favicon-generator'], true))
                    <div class="image-preview-grid" data-favicon-preview></div>
                @elseif($mode === 'color-picker-from-image')
                    <canvas class="picker-canvas" data-picker-canvas hidden></canvas>
                @else
                    <span>Result will appear here.</span>
                @endif
            </div>

            <textarea id="localOutput-{{ $mode }}" class="form-control text-output code-textarea" rows="9" readonly data-local-output></textarea>

            <div class="result-actions">
                <button class="btn btn-primary" type="button" data-copy-target="localOutput-{{ $mode }}">Copy Values</button>
                @if($mode === 'barcode-generator')
                    <button class="btn btn-secondary" type="button" data-local-download="svg">Download SVG</button>
                    <button class="btn btn-secondary" type="button" data-local-download="png">Download PNG</button>
                @elseif($mode === 'image-to-base64-converter')
                    <button class="btn btn-secondary" type="button" data-local-download="txt">Download TXT</button>
                @elseif($mode === 'robots-txt-generator')
                    <button class="btn btn-secondary" type="button" data-local-download="robots">Download robots.txt</button>
                @elseif($mode === 'csv-to-json-converter')
                    <button class="btn btn-secondary" type="button" data-local-download="json">Download JSON</button>
                @elseif($mode === 'webp-to-png-converter')
                    <button class="btn btn-secondary" type="button" data-local-download="png-batch">Download PNG</button>
                @elseif(in_array($mode, ['ico-favicon-generator', 'favicon-generator'], true))
                    <button class="btn btn-secondary" type="button" data-local-download="ico">Download .ico</button>
                    <button class="btn btn-secondary" type="button" data-local-download="favicon-zip">Download ZIP</button>
                @elseif($mode === 'color-picker-from-image')
                    <button class="btn btn-secondary" type="button" data-local-download="palette">Download Palette</button>
                @endif
                <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
                <span class="copy-status" data-copy-status></span>
            </div>
        </div>
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => $toolMeta['category'].' ad'])
@endsection
