@extends('layouts.app')

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ $toolMeta['category'] }} tool</span>
        <h1>{{ $toolMeta['name'] }}</h1>
        <p>{{ $toolMeta['desc'] }}</p>
    </section>

    <section class="tool-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('finance.calculate', $toolMeta['slug']) }}">
                @csrf

                @foreach($toolMeta['fields'] as $field)
                    <div class="mb-3">
                        <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                        @if($field['type'] === 'select')
                            <select id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" required>
                                @foreach($field['options'] as $value => $label)
                                    <option value="{{ $value }}" @selected((string) old($field['name'], $validated[$field['name']] ?? $field['default']) === (string) $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input
                                id="{{ $field['name'] }}"
                                type="{{ $field['type'] }}"
                                name="{{ $field['name'] }}"
                                class="form-control"
                                min="0"
                                step="{{ $field['step'] ?? '0.01' }}"
                                required
                                value="{{ old($field['name'], $validated[$field['name']] ?? $field['default'] ?? '') }}"
                            >
                        @endif
                    </div>
                @endforeach

                <button class="btn btn-primary w-100" type="submit">Calculate</button>
            </form>
        </div>

        @isset($result)
            <div class="result-box">
                <span class="eyebrow">Result</span>
                <div class="finance-result-grid">
                    @foreach($result as $label => $value)
                        <div class="finance-result-item">
                            <span>{{ $label }}</span>
                            <strong>
                                @if(str_contains($label, 'CAGR'))
                                    {{ number_format($value, 2) }}%
                                @else
                                    &#8377; {{ number_format($value, 2) }}
                                @endif
                            </strong>
                        </div>
                    @endforeach
                </div>
            </div>
        @endisset
    </section>

    @include('partials.ad-slot', ['class' => 'ad-inline', 'label' => 'Finance calculator ad'])
@endsection
