@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Utility tool</span>
        <h1>QR Code Generator</h1>
        <p>Generate QR codes for URLs, text, contact details or short notes.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('qr.generate') }}">
                @csrf

                <div class="mb-3">
                    <label for="text">Enter Text / URL</label>
                    <input id="text" type="text" name="text" class="form-control" maxlength="500" required value="{{ old('text', $text ?? '') }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Generate QR</button>
            </form>
        </div>

        @isset($qr)
            <div class="result-box text-center qr-output">
                <span class="eyebrow">QR code</span>
                <div class="mt-3">{!! $qr !!}</div>
                <a href="https://wa.me/?text={{ rawurlencode('QR Generated for: ' . $text) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'QR ad'])
@endsection
