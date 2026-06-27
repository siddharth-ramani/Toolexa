@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Utility tool</span>
        <h1>Unit Converter</h1>
        <p>Convert common distance and weight units in a clean, quick interface.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('unit.convert') }}">
                @csrf

                <div class="mb-3">
                    <label for="value">Enter Value</label>
                    <input id="value" type="number" name="value" class="form-control" min="0" step="0.01" required value="{{ old('value', $value ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="type">Select Conversion</label>
                    <select id="type" name="type" class="form-control">
                        <option value="km_to_m" @selected(($type ?? '') === 'km_to_m')>Kilometer to Meter</option>
                        <option value="m_to_km" @selected(($type ?? '') === 'm_to_km')>Meter to Kilometer</option>
                        <option value="kg_to_g" @selected(($type ?? '') === 'kg_to_g')>Kilogram to Gram</option>
                        <option value="g_to_kg" @selected(($type ?? '') === 'g_to_kg')>Gram to Kilogram</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100" type="submit">Convert Unit</button>
            </form>
        </div>

        @isset($result)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <span class="result-value">{{ number_format($result, 4) }}</span>
                <a href="https://wa.me/?text={{ rawurlencode('Converted Value: ' . number_format($result, 4)) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Utility ad'])
@endsection
