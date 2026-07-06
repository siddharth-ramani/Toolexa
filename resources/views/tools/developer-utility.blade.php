@extends('layouts.app')

@php
    $mode = $toolMeta['slug'];
    $inputLabel = match ($mode) {
        'xml-to-json-converter' => 'Enter XML',
        'css-minifier', 'css-beautifier' => 'Enter CSS',
        'html-to-markdown-converter' => 'Enter HTML',
        'markdown-to-html-converter' => 'Enter Markdown',
        'base64-encoder-decoder' => 'Enter Text or Base64',
        'sql-formatter' => 'Enter SQL',
        'html-formatter' => 'Enter HTML',
        default => 'Enter JSON',
    };
    $outputLabel = match ($mode) {
        'json-to-xml-converter' => 'XML Output',
        'xml-to-json-converter' => 'JSON Output',
        'css-minifier', 'css-beautifier' => 'CSS Output',
        'html-to-markdown-converter' => 'Markdown Output',
        'markdown-to-html-converter' => 'HTML Output',
        'base64-encoder-decoder' => 'Output',
        'sql-formatter' => 'SQL Output',
        'html-formatter' => 'HTML Output',
        default => 'JSON Output',
    };
    $placeholder = match ($mode) {
        'xml-to-json-converter' => '<root><name>Toolexa</name></root>',
        'css-minifier', 'css-beautifier' => 'body { color: #102033; margin: 0; }',
        'html-to-markdown-converter' => '<h1>Toolexa</h1><p>Fast developer tools</p>',
        'markdown-to-html-converter' => '# Toolexa'."\n\n".'Fast **developer** tools.',
        'base64-encoder-decoder' => 'Toolexa developer tools',
        'sql-formatter' => 'select id, name from users where active = 1 order by name;',
        'html-formatter' => '<main><h1>Toolexa</h1><p>Fast tools</p></main>',
        default => '{"name":"Toolexa","type":"tool"}',
    };
@endphp

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ $toolMeta['category'] }} tool</span>
        <h1>{{ $toolMeta['name'] }}</h1>
        <p>{{ $toolMeta['desc'] }}</p>
    </section>

    <section class="tool-layout developer-tool" data-developer-tool data-developer-mode="{{ $mode }}">
        <div class="form-panel">
            <div class="mb-3">
                <label for="developerInput-{{ $mode }}">{{ $inputLabel }}</label>
                <textarea id="developerInput-{{ $mode }}" class="form-control code-textarea" rows="14" maxlength="200000" placeholder="{{ $placeholder }}" data-developer-input></textarea>
            </div>

            <div class="tool-actions compact-actions">
                @if($mode === 'json-formatter')
                    <button class="btn btn-primary" type="button" data-developer-action="beautify">Beautify JSON</button>
                    <button class="btn btn-secondary" type="button" data-developer-action="minify">Minify JSON</button>
                    <button class="btn" type="button" data-developer-action="validate">Validate JSON</button>
                @elseif($mode === 'json-validator')
                    <button class="btn btn-primary" type="button" data-developer-action="validate">Validate JSON</button>
                @elseif($mode === 'json-to-xml-converter')
                    <button class="btn btn-primary" type="button" data-developer-action="convert">Convert JSON to XML</button>
                @elseif($mode === 'xml-to-json-converter')
                    <button class="btn btn-primary" type="button" data-developer-action="convert">Convert XML to JSON</button>
                    <button class="btn" type="button" data-developer-action="validate">Validate XML</button>
                @elseif($mode === 'css-minifier')
                    <button class="btn btn-primary" type="button" data-developer-action="minify">Minify CSS</button>
                @elseif($mode === 'css-beautifier')
                    <button class="btn btn-primary" type="button" data-developer-action="beautify">Format CSS</button>
                @elseif($mode === 'html-to-markdown-converter')
                    <button class="btn btn-primary" type="button" data-developer-action="convert">Convert HTML to Markdown</button>
                @elseif($mode === 'markdown-to-html-converter')
                    <button class="btn btn-primary" type="button" data-developer-action="convert">Convert Markdown to HTML</button>
                @elseif($mode === 'base64-encoder-decoder')
                    <button class="btn btn-primary" type="button" data-developer-action="encode">Encode Base64</button>
                    <button class="btn btn-secondary" type="button" data-developer-action="decode">Decode Base64</button>
                @elseif($mode === 'sql-formatter')
                    <button class="btn btn-primary" type="button" data-developer-action="beautify">Beautify SQL</button>
                    <button class="btn btn-secondary" type="button" data-developer-action="minify">Minify SQL</button>
                @else
                    <button class="btn btn-primary" type="button" data-developer-action="beautify">Beautify HTML</button>
                    <button class="btn btn-secondary" type="button" data-developer-action="minify">Minify HTML</button>
                @endif
                <button class="btn btn-secondary" type="button" data-developer-clear>Clear</button>
            </div>

            <p class="tool-note">Processing runs locally in your browser. Toolexa does not upload or permanently store your input.</p>
        </div>

        <div class="result-box">
            <span class="eyebrow">Result</span>
            <div class="developer-status" data-developer-status>Result will appear here.</div>
            <div class="developer-error" data-developer-error hidden></div>

            <label for="developerOutput-{{ $mode }}">{{ $outputLabel }}</label>
            <textarea id="developerOutput-{{ $mode }}" class="form-control text-output code-textarea" rows="14" readonly data-developer-output></textarea>

            @if($mode === 'markdown-to-html-converter')
                <div class="markdown-preview" data-markdown-preview hidden>
                    <span class="eyebrow">Live preview</span>
                    <div data-markdown-preview-body></div>
                </div>
            @endif

            <div class="result-actions">
                <button class="btn btn-primary" type="button" data-copy-target="developerOutput-{{ $mode }}">
                    {{ $mode === 'markdown-to-html-converter' ? 'Copy HTML' : 'Copy Result' }}
                </button>
                @if($mode === 'json-formatter')
                    <button class="btn btn-secondary" type="button" data-developer-download="json">Download .json</button>
                @elseif($mode === 'json-to-xml-converter')
                    <button class="btn btn-secondary" type="button" data-developer-download="xml">Download XML</button>
                @elseif($mode === 'xml-to-json-converter')
                    <button class="btn btn-secondary" type="button" data-developer-download="json">Download JSON</button>
                @elseif($mode === 'html-formatter')
                    <button class="btn btn-secondary" type="button" data-developer-download="html">Download</button>
                @elseif($mode === 'css-minifier')
                    <button class="btn btn-secondary" type="button" data-developer-download="css">Download .css</button>
                @elseif($mode === 'css-beautifier')
                    <button class="btn btn-secondary" type="button" data-developer-download="css">Download</button>
                @elseif($mode === 'html-to-markdown-converter')
                    <button class="btn btn-secondary" type="button" data-developer-download="md">Download .md</button>
                @elseif($mode === 'markdown-to-html-converter')
                    <button class="btn btn-secondary" type="button" data-developer-download="html">Download HTML</button>
                @elseif($mode === 'base64-encoder-decoder')
                    <button class="btn btn-secondary" type="button" data-developer-download="txt">Download</button>
                @elseif($mode === 'sql-formatter')
                    <button class="btn btn-secondary" type="button" data-developer-download="sql">Download</button>
                @endif
                <button class="btn btn-success" type="button" data-share-url>Share Tool</button>
                <span class="copy-status" data-copy-status></span>
            </div>
        </div>
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => $toolMeta['category'].' ad'])
@endsection
