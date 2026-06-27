@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Security tool</span>
        <h1>Password Generator</h1>
        <p>Create a strong random password for accounts, apps and websites.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('password.generate') }}">
                @csrf

                <div class="mb-3">
                    <label for="length">Password Length</label>
                    <input id="length" type="number" name="length" class="form-control" min="6" max="64" required value="{{ old('length', $length ?? 12) }}">
                </div>

                <button class="btn btn-primary w-100" type="submit">Generate Password</button>
            </form>
        </div>

        @isset($password)
            <div class="result-box text-center">
                <span class="eyebrow">Generated password</span>
                <span class="result-value">{{ $password }}</span>
                <a href="https://wa.me/?text={{ rawurlencode('Generated Password: ' . $password) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share on WhatsApp</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Security ad'])
@endsection
