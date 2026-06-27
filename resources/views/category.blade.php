@extends('layouts.app')

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">Category</span>
        <h1>{{ $category['name'] }} Tools</h1>
        <p>Browse free {{ strtolower($category['name']) }} calculators and tools.</p>
    </section>

    <section class="info-panel">
        <span class="eyebrow">{{ $tools->total() }} tools</span>
        <h2>{{ $category['name'] }} Tools</h2>

        <div class="tool-grid">
            @foreach($tools as $tool)
                <a class="card tool-card" href="{{ url('tools/'.$tool['slug']) }}">
                    <span class="tool-icon">{{ $tool['icon'] }}</span>
                    <h3>{{ $tool['name'] }}</h3>
                    <p>{{ $tool['desc'] }}</p>
                    <span class="btn btn-primary btn-sm">Use Tool</span>
                </a>
            @endforeach
        </div>

        {{ $tools->links('partials.pagination') }}
    </section>
@endsection
