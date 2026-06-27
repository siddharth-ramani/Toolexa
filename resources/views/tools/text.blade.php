@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Text tool</span>
        <h1>Text Case Converter</h1>
        <p>Convert text into uppercase, lowercase and title case formats.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('text.convert') }}">
                @csrf

                <div class="mb-3">
                    <label for="text">Enter Text</label>
                    <textarea id="text" name="text" class="form-control" rows="5" maxlength="5000" required>{{ old('text', $text ?? '') }}</textarea>
                </div>

                <button class="btn btn-primary w-100" type="submit">Convert Text</button>
            </form>
        </div>

        @isset($text)
            <div class="result-box">
                <span class="eyebrow">Converted text</span>
                <p><strong>UPPERCASE</strong><br>{{ $upper }}</p>
                <p><strong>lowercase</strong><br>{{ $lower }}</p>
                <p><strong>Title Case</strong><br>{{ $title }}</p>
                <a href="https://wa.me/?text={{ rawurlencode('Converted Text: ' . $upper) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Text ad'])
@endsection
