@extends('layouts.app')

@php($categoryLabel = str_contains($category['name'], 'Tools') ? $category['name'] : $category['name'].' Tools')

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Category</span>
        <h1>{{ $categoryLabel }}</h1>
        <p>Browse free {{ strtolower($category['name']) }} calculators and tools.</p>
    </section>

    <section class="info-panel category-tools-panel">
        <div class="section-head category-tools-head">
            <div>
                <span class="eyebrow">{{ $tools->total() }} tools</span>
                <h2>{{ $categoryLabel }}</h2>
            </div>
        </div>

        <div class="category-tool-grid">
            @foreach($tools as $tool)
                <a class="card tool-card category-tool-card" href="{{ url('tools/'.$tool['slug']) }}">
                    <span class="tool-icon">{{ $tool['icon'] }}</span>
                    <span class="category-tool-body">
                        <h3>{{ $tool['name'] }}</h3>
                        <p>{{ $tool['desc'] }}</p>
                        <span class="btn btn-primary btn-sm">Use Tool</span>
                    </span>
                </a>
            @endforeach
        </div>

        {{ $tools->links('partials.pagination') }}
    </section>
@endsection
