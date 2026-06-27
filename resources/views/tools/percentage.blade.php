@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Math tool</span>
        <h1>Percentage Calculator</h1>
        <p>Find what percentage one value is of a total number.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('percentage.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="value">Value</label>
                    <input id="value" type="number" name="value" class="form-control" min="0" step="0.01" required value="{{ old('value', $value ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="total">Total</label>
                    <input id="total" type="number" name="total" class="form-control" min="1" step="0.01" required value="{{ old('total', $total ?? '') }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Calculate Percentage</button>
            </form>
        </div>

        @isset($percentage)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <span class="result-value">{{ number_format($percentage, 2) }}%</span>
                <a href="https://wa.me/?text={{ rawurlencode('Percentage: ' . number_format($percentage, 2) . '%') }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Math ad'])
@endsection
