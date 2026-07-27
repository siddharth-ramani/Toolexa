@props(['references'])

@if(count($references))
    <section class="info-panel comparison-references" aria-labelledby="comparison-references-heading">
        <span class="eyebrow">Primary sources</span>
        <h2 id="comparison-references-heading">Official References</h2>
        <p>Use these official standards and authorities to confirm information that can change over time.</p>
        <ul>
            @foreach($references as $reference)
                <li><a href="{{ $reference['url'] }}" target="_blank" rel="noopener noreferrer">{{ $reference['name'] }} <span aria-hidden="true">↗</span></a></li>
            @endforeach
        </ul>
    </section>
@endif
