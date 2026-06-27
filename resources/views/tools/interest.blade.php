@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Finance tool</span>
        <h1>Simple Interest Calculator</h1>
        <p>Calculate simple interest from principal amount, interest rate and time period.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('interest.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="principal">Principal Amount (&#8377;)</label>
                    <input id="principal" type="number" name="principal" class="form-control" min="1" step="0.01" required value="{{ old('principal', $P ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="rate">Interest Rate (% per year)</label>
                    <input id="rate" type="number" name="rate" class="form-control" min="1" step="0.01" required value="{{ old('rate', $R ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="time">Time (years)</label>
                    <input id="time" type="number" name="time" class="form-control" min="1" step="0.01" required value="{{ old('time', $T ?? '') }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Calculate Interest</button>
            </form>
        </div>

        @isset($interest)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <h4>Interest</h4>
                <span class="result-value">&#8377; {{ number_format($interest, 2) }}</span>
                <p>Total Amount: <strong>&#8377; {{ number_format($total, 2) }}</strong></p>
                <a href="https://wa.me/?text={{ rawurlencode('Interest: Rs ' . number_format($interest, 2) . ' Total: Rs ' . number_format($total, 2)) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Finance ad'])
@endsection
