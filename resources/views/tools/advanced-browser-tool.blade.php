@extends('layouts.app')

@php($mode = $toolMeta['slug'])

@section('content')
<section class="tool-hero"><span class="eyebrow">{{ $toolMeta['category'] }} tool</span><h1>{{ $toolMeta['name'] }}</h1><p>{{ $toolMeta['desc'] }}</p></section>

<section class="form-panel advanced-tool" data-advanced-tool data-mode="{{ $mode }}">
    @if($mode === 'sha-256-hash-generator')
        <label for="hashInput">Text to hash</label>
        <textarea id="hashInput" class="form-control code-textarea" rows="8" placeholder="Type or paste text…" data-hash-input></textarea>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="hash">Generate SHA-256</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <label for="hashOutput">SHA-256 hash</label><textarea id="hashOutput" class="form-control code-textarea" rows="3" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="hashOutput">Copy Hash</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'url-parser')
        <label for="urlInput">URL</label><input id="urlInput" class="form-control" type="url" placeholder="https://example.com:8080/path?name=value#section" data-url-input>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="url">Parse URL</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <div class="browser-result-panel" data-url-result><span>Parsed URL components will appear here.</span></div>
        <textarea id="urlOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="urlOutput">Copy Parsed Data</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'color-palette-generator')
        <div class="palette-grid" data-palette></div>
        <textarea id="paletteOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-action="palette">Generate Palette</button><button class="btn btn-secondary" type="button" data-export>Export Palette</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'pdf-splitter')
        <label for="splitPdfInput">Choose PDF</label><input id="splitPdfInput" class="form-control" type="file" accept="application/pdf" data-pdf-input>
        <p class="tool-note" data-page-count>Select a PDF to preview its page count.</p>
        <label for="pageRanges">Pages or ranges</label><input id="pageRanges" class="form-control" type="text" placeholder="Example: 1-3, 5, 8-10" data-ranges>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="split">Extract Selected Pages</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <div class="browser-result-panel" data-pdf-result><span>The extraction summary will appear here.</span></div>
        <div class="result-actions"><button class="btn btn-success disabled" type="button" data-download>Download New PDF</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
        <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js" defer></script>
    @elseif($mode === 'open-graph-meta-tag-generator')
        <div class="image-control-grid"><div><label for="ogTitle">Website title</label><input id="ogTitle" class="form-control" type="text" maxlength="200" data-og-title></div><div><label for="ogType">Type</label><select id="ogType" class="form-control" data-og-type><option value="website">Website</option><option value="article">Article</option><option value="product">Product</option><option value="profile">Profile</option></select></div></div>
        <label for="ogDescription">Description</label><textarea id="ogDescription" class="form-control" rows="4" maxlength="500" data-og-description></textarea>
        <div class="image-control-grid"><div><label for="ogImage">Image URL</label><input id="ogImage" class="form-control" type="url" placeholder="https://example.com/social-image.jpg" data-og-image></div><div><label for="ogUrl">Website URL</label><input id="ogUrl" class="form-control" type="url" placeholder="https://example.com/page" data-og-url></div></div>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="og">Generate Meta Tags</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <label for="ogOutput">Open Graph HTML</label><textarea id="ogOutput" class="form-control code-textarea" rows="8" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="ogOutput">Copy HTML</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'html-entity-encoder-decoder')
        <label for="entityMode">Conversion mode</label><select id="entityMode" class="form-control" data-entity-mode><option value="encode">Encode HTML Entities</option><option value="decode">Decode HTML Entities</option></select>
        <label for="entityInput">Input</label><textarea id="entityInput" class="form-control code-textarea" rows="9" placeholder="Enter text or HTML entities…" data-entity-input></textarea>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="entity">Convert</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <label for="entityOutput">Output</label><textarea id="entityOutput" class="form-control code-textarea" rows="9" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="entityOutput">Copy Result</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'time-zone-converter')
        <datalist id="timeZoneList"></datalist>
        <div class="image-control-grid"><div><label for="sourceZone">From time zone</label><input id="sourceZone" class="form-control" list="timeZoneList" placeholder="Search time zones" data-source-zone></div><div><label for="targetZone">To time zone</label><input id="targetZone" class="form-control" list="timeZoneList" placeholder="Search time zones" data-target-zone></div></div>
        <label for="zoneDateTime">Source date and time</label><input id="zoneDateTime" class="form-control" type="datetime-local" data-zone-datetime>
        <div class="current-zone-grid"><div class="compare-summary"><strong>Current source time</strong><span data-source-current>—</span></div><div class="compare-summary"><strong>Current destination time</strong><span data-target-current>—</span></div></div>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="timezone">Convert Time Zone</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <textarea id="timezoneOutput" class="form-control code-textarea" rows="5" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="timezoneOutput">Copy Result</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'uuid-batch-generator')
        <label for="uuidQuantity">Quantity (1–1,000)</label><input id="uuidQuantity" class="form-control" type="number" min="1" max="1000" step="1" value="10" data-uuid-quantity>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="uuid">Generate UUIDs</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <p class="compare-summary" data-uuid-summary>Choose a quantity and generate UUID v4 values.</p>
        <textarea id="uuidOutput" class="form-control code-textarea" rows="16" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="uuidOutput">Copy All</button><button class="btn btn-secondary" type="button" data-uuid-download>Download TXT</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'xml-sitemap-generator')
        <div data-sitemap-rows></div>
        <div class="tool-actions compact-actions"><button class="btn btn-secondary" type="button" data-sitemap-add>Add URL</button><button class="btn btn-primary" type="button" data-action="sitemap">Generate Sitemap</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <label for="sitemapOutput">Generated XML</label><textarea id="sitemapOutput" class="form-control code-textarea" rows="14" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="sitemapOutput">Copy XML</button><button class="btn btn-secondary" type="button" data-sitemap-download>Download sitemap.xml</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'json-minifier')
        <label for="jsonMinInput">JSON input</label><textarea id="jsonMinInput" class="form-control code-textarea" rows="12" placeholder='{"name": "Toolexa", "active": true}' data-json-min-input></textarea>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="json-minify">Minify JSON</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div><p class="compare-summary" data-json-status>Enter valid JSON to generate compact output.</p>
        <label for="jsonMinOutput">Minified JSON</label><textarea id="jsonMinOutput" class="form-control code-textarea" rows="10" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="jsonMinOutput">Copy JSON</button><button class="btn btn-secondary" type="button" data-json-download>Download JSON</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'css-gradient-generator')
        <div class="image-control-grid"><div><label for="gradientType">Gradient type</label><select id="gradientType" class="form-control" data-gradient-type><option value="linear">Linear Gradient</option><option value="radial">Radial Gradient</option></select></div><div><label for="gradientAngle">Angle: <span data-angle-label>90°</span></label><input id="gradientAngle" class="form-control" type="range" min="0" max="360" value="90" data-gradient-angle></div></div>
        <div class="image-control-grid"><div><label for="gradientColorOne">Start color</label><input id="gradientColorOne" class="form-control color-input" type="color" value="#0f766e" data-gradient-one></div><div><label for="gradientColorTwo">End color</label><input id="gradientColorTwo" class="form-control color-input" type="color" value="#7c3aed" data-gradient-two></div></div>
        <div class="gradient-preview" data-gradient-preview aria-label="Live gradient preview"></div>
        <label for="gradientOutput">Generated CSS</label><textarea id="gradientOutput" class="form-control code-textarea" rows="4" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="gradientOutput">Copy CSS</button><button class="btn btn-secondary" type="button" data-gradient-download>Download CSS</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'png-to-svg-converter')
        <div class="alert-box"><p><strong>Best for simple graphics:</strong> Photographs, gradients, shadows and detailed images can create very large SVG files and will not become clean editable vector paths.</p></div>
        <label for="pngSvgInput">Upload PNG</label><input id="pngSvgInput" class="form-control" type="file" accept="image/png" data-png-input>
        <label for="colorStep">Color simplification</label><select id="colorStep" class="form-control" data-color-step><option value="1">None — exact colors</option><option value="16" selected>Medium — recommended</option><option value="32">Strong — fewer colors</option><option value="64">Maximum — smallest output</option></select>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="png-svg">Convert to SVG</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div><p class="compare-summary" data-svg-status>Upload a PNG to begin local tracing.</p>
        <div class="svg-preview" data-svg-preview><span>SVG preview will appear here.</span></div><textarea id="svgOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="svgOutput">Copy SVG</button><button class="btn btn-secondary" type="button" data-svg-download>Download SVG</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'text-sorter')
        <div class="compare-inputs"><div><label for="sortInput">Input lines</label><textarea id="sortInput" class="form-control code-textarea" rows="14" data-sort-input></textarea></div><div><label for="sortOutput">Sorted lines</label><textarea id="sortOutput" class="form-control code-textarea" rows="14" readonly data-output></textarea></div></div>
        <div class="image-control-grid"><div><label for="sortMode">Sort order</label><select id="sortMode" class="form-control" data-sort-mode><option value="az">A–Z</option><option value="za">Z–A</option><option value="length">By Length</option></select></div><div class="option-grid"><label><input type="checkbox" data-sort-ignore> Ignore Case</label><label><input type="checkbox" data-sort-empty> Remove Empty Lines</label></div></div>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="sort">Sort Text</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div><p class="compare-summary" data-sort-summary>Sort summary will appear here.</p>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="sortOutput">Copy Result</button><button class="btn btn-secondary" type="button" data-sort-download>Download TXT</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'image-rotator-flipper')
        <label for="rotateInput">Choose images</label><input id="rotateInput" class="form-control" type="file" accept="image/png,image/jpeg,image/webp" multiple data-rotate-input>
        <div class="image-control-grid"><div><label for="imageOperation">Operation</label><select id="imageOperation" class="form-control" data-image-operation><option value="90">Rotate 90° clockwise</option><option value="180">Rotate 180°</option><option value="270">Rotate 270° clockwise</option><option value="flip-h">Flip Horizontal</option><option value="flip-v">Flip Vertical</option></select></div><div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="transform-images">Apply to Batch</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div></div>
        <p class="compare-summary" data-image-status>Select one or more images to begin.</p><div class="image-preview-grid" data-transform-previews></div>
        <div class="result-actions"><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'regex-tester')
        <div class="image-control-grid"><div><label for="regexPattern">Regular expression</label><input id="regexPattern" class="form-control code-textarea" type="text" placeholder="\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b" data-regex-pattern></div><div><label for="regexFlags">Flags</label><input id="regexFlags" class="form-control" type="text" value="gi" maxlength="6" placeholder="gim" data-regex-flags></div></div>
        <label for="regexExample">Common examples</label><select id="regexExample" class="form-control" data-regex-example><option value="">Choose an example</option><option value="email">Email addresses</option><option value="url">HTTP/HTTPS URLs</option><option value="number">Numbers</option><option value="space">Repeated whitespace</option></select>
        <label for="regexText">Sample text</label><textarea id="regexText" class="form-control code-textarea" rows="10" data-regex-text></textarea>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="regex">Test Regex</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <p class="compare-summary" data-regex-status>Matches and errors will appear here.</p><div class="regex-highlight" data-regex-highlight></div><textarea id="regexOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="regexOutput">Copy Results</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'uuid-decoder-inspector')
        <label for="inspectUuid">UUID</label><input id="inspectUuid" class="form-control code-textarea" type="text" placeholder="550e8400-e29b-41d4-a716-446655440000" data-inspect-uuid>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="inspect-uuid">Inspect UUID</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <div class="browser-result-panel" data-uuid-inspection><span>UUID structure details will appear here.</span></div><textarea id="uuidInspectOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="uuidInspectOutput">Copy Result</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'reading-time-calculator')
        <label for="readingText">Text</label><textarea id="readingText" class="form-control" rows="14" placeholder="Paste an article, script or other text…" data-reading-text></textarea>
        <label for="readingSpeed">Reading speed</label><select id="readingSpeed" class="form-control" data-reading-speed><option value="150">Careful — 150 words/minute</option><option value="200" selected>Average — 200 words/minute</option><option value="250">Fast — 250 words/minute</option><option value="300">Scanning — 300 words/minute</option></select>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="reading">Calculate Reading Time</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <div class="reading-result-grid" data-reading-results></div><textarea id="readingOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="readingOutput">Copy Results</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @elseif($mode === 'screen-resolution-checker')
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="screen">Refresh Details</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button></div>
        <div class="reading-result-grid" data-screen-results></div><textarea id="screenOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="screenOutput">Copy Results</button><span data-copy-status></span></div>
    @else
        <div class="compare-inputs"><div><label for="compareOriginal">Original text</label><textarea id="compareOriginal" class="form-control code-textarea" rows="12" data-original></textarea></div><div><label for="compareChanged">Changed text</label><textarea id="compareChanged" class="form-control code-textarea" rows="12" data-changed></textarea></div></div>
        <div class="tool-actions compact-actions"><button class="btn btn-primary" type="button" data-action="compare">Compare Text</button><button class="btn btn-secondary" type="button" data-clear>Clear</button></div>
        <div class="compare-summary" data-compare-summary>Comparison summary will appear here.</div><div class="diff-output" data-diff></div>
        <textarea id="compareOutput" class="copy-source" readonly data-output></textarea>
        <div class="result-actions"><button class="btn btn-primary" type="button" data-copy-target="compareOutput">Copy Result</button><button class="btn btn-success" type="button" data-share-url>Share Tool</button><span data-copy-status></span></div>
    @endif
    <p class="tool-note">Processing runs locally in your browser. Toolexa does not upload or permanently store your input.</p>
</section>

@include('partials.ad-slot', ['class' => 'ad-inline', 'label' => $toolMeta['category'].' ad'])
@endsection
