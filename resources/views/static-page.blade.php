@extends('layouts.app')

@section('content')
    <section class="tool-hero">
        <span class="eyebrow">{{ config('app.name', 'Toolexa') }}</span>
        <h1>{{ $page['heading'] }}</h1>
        <p>{{ $page['description'] }}</p>
    </section>

    <section class="info-panel static-content">
        @foreach($page['content'] as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach

        @foreach($page['sections'] ?? [] as $section)
            <article class="static-section">
                <h2>{{ $section['heading'] }}</h2>

                @if(!empty($section['body']))
                    @if(!empty($section['email']))
                        <p>
                            @if($section['body'] === 'support@toolexa.in')
                                <a href="mailto:support@toolexa.in">support@toolexa.in</a>
                            @else
                                {{ \Illuminate\Support\Str::before($section['body'], 'support@toolexa.in') }}<a href="mailto:support@toolexa.in">support@toolexa.in</a>{{ \Illuminate\Support\Str::after($section['body'], 'support@toolexa.in') }}
                            @endif
                        </p>
                    @else
                        <p>{{ $section['body'] }}</p>
                    @endif
                @endif

                @if(!empty($section['items']))
                    <ul>
                        @foreach($section['items'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif
            </article>
        @endforeach
    </section>
@endsection
