@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Math tool</span>
        <h1>Percentage Calculator</h1>
        @include('partials.tool-editorial-meta')
        <p>Find a percentage of a number, compare a value with a total, or measure percentage increase and decrease.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('percentage.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="mode">What do you want to calculate?</label>
                    <select id="mode" name="mode" class="form-control" required>
                        <option value="percent_of" @selected(old('mode', $mode ?? 'percent_of') === 'percent_of')>What is X% of Y?</option>
                        <option value="find_percent" @selected(old('mode', $mode ?? '') === 'find_percent')>X is what percent of Y?</option>
                        <option value="percent_change" @selected(old('mode', $mode ?? '') === 'percent_change')>Percentage increase or decrease</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="first_number">First number</label>
                    <input id="first_number" type="number" name="first_number" class="form-control @error('first_number') is-invalid @enderror" step="any" required value="{{ old('first_number', $first ?? '') }}" aria-describedby="first-number-help">
                    <small id="first-number-help">Enter the percentage, compared value, or original value—based on the option above.</small>
                    @error('first_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="second_number">Second number</label>
                    <input id="second_number" type="number" name="second_number" class="form-control @error('second_number') is-invalid @enderror" step="any" required value="{{ old('second_number', $second ?? '') }}" aria-describedby="second-number-help">
                    <small id="second-number-help">Enter the base number, total, or new value—based on the option above.</small>
                    @error('second_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button class="btn btn-primary w-100" type="submit">Calculate</button>
            </form>
        </div>

        @isset($result)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <p>{{ $result['summary'] }}</p>
                <span class="result-value">{{ number_format($result['value'], 2) }}{{ $result['suffix'] }}</span>
                <small class="d-block mt-2">Calculation: {{ $result['calculation'] }}</small>
                <a href="https://wa.me/?text={{ rawurlencode($result['summary'] . ': ' . number_format($result['value'], 2) . $result['suffix']) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share result</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Math ad'])
@endsection
