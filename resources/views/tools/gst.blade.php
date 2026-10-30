@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Finance tool</span>
        <h1>GST Calculator</h1>
        @include('partials.tool-editorial-meta')
        <p>Add GST to a tax-exclusive amount or extract the taxable value and GST from an inclusive total.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('gst.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="mode">Price type</label>
                    <select id="mode" name="mode" class="form-control" required>
                        <option value="exclusive" @selected(old('mode', $mode ?? 'exclusive') === 'exclusive')>GST exclusive — add GST</option>
                        <option value="inclusive" @selected(old('mode', $mode ?? '') === 'inclusive')>GST inclusive — extract GST</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="amount">Amount (&#8377;)</label>
                    <input id="amount" type="number" name="amount" class="form-control" min="1" step="0.01" required value="{{ old('amount', $amount ?? '') }}">
                    <small>Enter the price before GST in exclusive mode or the final price containing GST in inclusive mode.</small>
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
                <h4>Taxable Value</h4>
                <span class="result-value">&#8377; {{ number_format($taxable, 2) }}</span>
                <h4>GST Amount</h4>
                <p><strong>&#8377; {{ number_format($gst, 2) }}</strong></p>
                <p>Total Amount: <strong>&#8377; {{ number_format($total, 2) }}</strong></p>
                <a href="https://wa.me/?text={{ rawurlencode('Taxable Value: Rs ' . number_format($taxable, 2) . ' GST: Rs ' . number_format($gst, 2) . ' Total: Rs ' . number_format($total, 2)) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share result</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Calculator ad'])
@endsection
