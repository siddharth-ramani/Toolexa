@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Finance tool</span>
        <h1>EMI Calculator</h1>
        <p>Plan monthly loan payments with EMI, total interest and total repayment in one view.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('emi.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="amount">Loan Amount (&#8377;)</label>
                    <input id="amount" type="number" name="amount" class="form-control" min="1" step="0.01" required value="{{ old('amount', $P ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="rate">Interest Rate (% per year)</label>
                    <input id="rate" type="number" name="rate" class="form-control" min="1" step="0.01" required value="{{ old('rate', $annualRate ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="tenure">Tenure (months)</label>
                    <input id="tenure" type="number" name="tenure" class="form-control" min="1" required value="{{ old('tenure', $N ?? '') }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Calculate EMI</button>
            </form>
        </div>

        @isset($emi)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <h4>Monthly EMI</h4>
                <span class="result-value">&#8377; {{ number_format($emi, 2) }}</span>
                <p>Total Interest: <strong>&#8377; {{ number_format($interest, 2) }}</strong></p>
                <p>Total Payment: <strong>&#8377; {{ number_format($total, 2) }}</strong></p>
                <a href="https://wa.me/?text={{ rawurlencode('EMI: Rs ' . number_format($emi, 2) . ' Total: Rs ' . number_format($total, 2)) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Loan ad'])
@endsection
