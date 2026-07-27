@props(['references'])
@if(count($references))
<section class="info-panel tool-quality-section" aria-labelledby="official-references-heading">
    <span class="eyebrow">Verify with primary sources</span><h2 id="official-references-heading">Official References</h2>
    <ul class="official-reference-list">
        @foreach($references as $reference)
            <li><a href="{{ $reference['url'] }}" target="_blank" rel="noopener noreferrer">{{ $reference['title'] }}</a><span>{{ $reference['publisher'] }}</span></li>
        @endforeach
    </ul>
</section>
@endif
