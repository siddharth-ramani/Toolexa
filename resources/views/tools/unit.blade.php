@extends('layouts.app')


@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Utility tool</span>
        <h1>Unit Converter</h1>
        @include('partials.tool-editorial-meta')
        <p>Convert common length, weight, temperature and volume units with labelled results.</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('unit.convert') }}">
                @csrf

                <div class="mb-3">
                    <label for="value">Enter Value</label>
                    <input id="value" type="number" name="value" class="form-control @error('value') is-invalid @enderror" step="any" required value="{{ old('value', $value ?? '') }}">
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="type">Select Conversion</label>
                    <select id="type" name="type" class="form-control" required>
                        @foreach(collect($conversionOptions)->groupBy('group') as $group => $options)
                            <optgroup label="{{ $group }}">
                                @foreach($options as $key => $option)
                                    <option value="{{ $key }}" @selected(old('type', $type ?? 'km_to_m') === $key)>{{ $option['label'] }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary w-100" type="submit">Convert Unit</button>
            </form>
        </div>

        @isset($result)
            <div class="result-box text-center">
                <span class="eyebrow">Result</span>
                <p>{{ $conversionLabel }}</p>
                <span class="result-value">{{ rtrim(rtrim(number_format($result, 6, '.', ''), '0'), '.') }} {{ $resultUnit }}</span>
                <a href="https://wa.me/?text={{ rawurlencode($conversionLabel . ': ' . rtrim(rtrim(number_format($result, 6, '.', ''), '0'), '.') . ' ' . $resultUnit) }}" target="_blank" rel="noopener" class="btn btn-success mt-3">Share result</a>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Utility ad'])
@endsection
