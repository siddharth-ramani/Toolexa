@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Shopping tool</span>
        <h1>Discount Calculator</h1>
        <p>Calculate sale price and savings before you buy.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('discount.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="price">Original Price (&#8377;)</label>
                    <input id="price" type="number" name="price" class="form-control" min="1" step="0.01" required value="{{ old('price', $price ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="discount">Discount (%)</label>
                    <input id="discount" type="number" name="discount" class="form-control" min="0" max="100" step="0.01" required value="{{ old('discount', $discount ?? '') }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Calculate Discount</button>
            </form>
        </div>

        @isset($finalPrice)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <h4>Final Price</h4>
                <span class="result-value">&#8377; {{ number_format($finalPrice, 2) }}</span>
                <p>You Save: <strong>&#8377; {{ number_format($discountAmount, 2) }}</strong></p>
                <a href="https://wa.me/?text={{ rawurlencode('Final Price: Rs ' . number_format($finalPrice, 2)) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Shopping ad'])
@endsection
