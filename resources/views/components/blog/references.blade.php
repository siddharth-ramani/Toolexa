@props(['references'])

@if(count($references))
    <section id="official-references" class="info-panel blog-references" aria-labelledby="official-references-heading">
        <span class="eyebrow">Source verification</span>
        <h2 id="official-references-heading">Official References</h2>
        <p>Use these primary sources to verify standards, rules or guidance that may change over time.</p>
        <ul>
            @foreach($references as $reference)
                <li><a href="{{ $reference['url'] }}" target="_blank" rel="noopener noreferrer">{{ $reference['name'] }} <span aria-hidden="true">↗</span></a></li>
            @endforeach
        </ul>
    </section>
@endif
