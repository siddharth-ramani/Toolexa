@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Utility tool</span>
        <h1>Age Calculator</h1>
        <p>Select your date of birth and get exact age in years, months and days.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('age.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" type="date" name="dob" class="form-control" required value="{{ old('dob', $dob ?? '') }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Calculate Age</button>
            </form>
        </div>

        @isset($years)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <span class="result-value">{{ $years }} Years</span>
                <p>{{ $months }} Months and {{ $days }} Days</p>
                <a href="https://wa.me/?text={{ rawurlencode('My Age: ' . $years . ' Years ' . $months . ' Months ' . $days . ' Days') }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Birthday ad'])
@endsection
