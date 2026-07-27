@props(['section'])

<article class="info-panel trust-section" id="{{ $section['id'] }}">
    <h2>{{ $section['heading'] }}</h2>

    @if(!empty($section['intro']))
        <p>{{ $section['intro'] }}</p>
    @endif

    @foreach($section['paragraphs'] ?? [] as $paragraph)
        <p>{{ $paragraph }}</p>
    @endforeach

    @if(!empty($section['steps']))
        <ol class="trust-process" aria-label="{{ $section['heading'] }}">
            @foreach($section['steps'] as $step)
                <li>
                    <span>{{ $loop->iteration }}</span>
                    <div><h3>{{ $step['title'] }}</h3><p>{{ $step['text'] }}</p></div>
                </li>
            @endforeach
        </ol>
    @endif

    @if(!empty($section['items']))
        <ul class="trust-checklist">
            @foreach($section['items'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif

    @if(!empty($section['badges']))
        <div class="trust-badges" aria-label="Supported browsers">
            @foreach($section['badges'] as $badge)
                <span>{{ $badge }}</span>
            @endforeach
        </div>
    @endif

    @if(!empty($section['link']))
        <a class="trust-text-link" href="{{ route($section['link']['route']) }}">{{ $section['link']['label'] }} →</a>
    @endif

    @if(!empty($section['cta']))
        <a class="btn btn-primary" href="{{ route($section['cta']['route'], $section['cta']['parameters'] ?? []) }}">{{ $section['cta']['label'] }}</a>
    @endif
</article>
