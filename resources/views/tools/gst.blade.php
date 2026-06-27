@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Finance tool</span>
        <h1>GST Calculator</h1>
        <p>Enter amount and GST rate to calculate GST amount and final total instantly.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('gst.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="amount">Amount (&#8377;)</label>
                    <input id="amount" type="number" name="amount" class="form-control" min="1" step="0.01" required value="{{ old('amount', $amount ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="rate">GST Rate (%)</label>
                    <input id="rate" type="number" name="rate" class="form-control" min="1" step="0.01" required value="{{ old('rate', $rate ?? '') }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Calculate GST</button>
            </form>
        </div>

        @isset($gst)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <h4>GST Amount</h4>
                <span class="result-value">&#8377; {{ number_format($gst, 2) }}</span>
                <p>Total Amount: <strong>&#8377; {{ number_format($total, 2) }}</strong></p>
                <a href="https://wa.me/?text={{ rawurlencode('GST Amount: Rs ' . number_format($gst, 2) . ' Total: Rs ' . number_format($total, 2)) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Calculator ad'])
@endsection
