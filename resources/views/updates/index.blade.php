@extends('layouts.app')

@section('content')
    <header class="tool-hero">
        <span class="eyebrow">Maintained in public</span>
        <h1>Toolexa Site Updates</h1>
        <p>A dated record of meaningful improvements to our tools, educational content, quality checks and user experience.</p>
        @if($updates->isNotEmpty())
            <p><strong>Last Updated:</strong> <time datetime="{{ $updates->first()['date'] }}">{{ \Illuminate\Support\Carbon::parse($updates->first()['date'])->format('F j, Y') }}</time></p>
        @endif
    </header>

    <section class="info-panel static-content" aria-labelledby="maintenance-heading">
        <h2 id="maintenance-heading">How this log supports accountability</h2>
        <p>We publish material changes here so visitors can see how Toolexa is reviewed and maintained. Entries describe shipped work, not planned features. Small copy corrections and routine dependency maintenance may not receive a separate entry.</p>
        <p>If a result appears incorrect or a guide is unclear, include the page URL and an example in your message. Reports help us reproduce the issue and decide what should be corrected next.</p>
        <p><a class="btn btn-primary" href="{{ route('page.show', 'contact') }}">Report an issue or request a tool</a></p>
    </section>

    <section class="info-panel static-content" aria-labelledby="updates-heading">
        <span class="eyebrow">Change log</span>
        <h2 id="updates-heading">Recent updates</h2>
        @forelse($updates as $update)
            <article class="static-section">
                <p><time datetime="{{ $update['date'] }}">{{ \Illuminate\Support\Carbon::parse($update['date'])->format('F j, Y') }}</time></p>
                <h3>{{ $update['title'] }}</h3>
                <p>{{ $update['summary'] }}</p>
                <ul>
                    @foreach($update['changes'] as $change)
                        <li>{{ $change }}</li>
                    @endforeach
                </ul>
                @if(!empty($update['links']))
                    <p>
                        @foreach($update['links'] as $link)
                            <a href="{{ url($link['url']) }}">{{ $link['label'] }}</a>{{ $loop->last ? '' : ' · ' }}
                        @endforeach
                    </p>
                @endif
            </article>
        @empty
            <p>No update entries are available yet.</p>
        @endforelse
    </section>

    <section class="info-panel static-content" aria-labelledby="quality-links-heading">
        <h2 id="quality-links-heading">Review our standards</h2>
        <p>Read the <a href="{{ route('trust.trust') }}">Trust Center</a>, <a href="{{ route('trust.editorial-policy') }}">Editorial Policy</a>, <a href="{{ route('trust.accuracy-policy') }}">Accuracy Policy</a> and <a href="{{ route('trust.how-we-test-tools') }}">tool testing process</a>.</p>
    </section>
@endsection
