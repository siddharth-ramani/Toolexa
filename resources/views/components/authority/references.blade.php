@props(['references'])

@if(count($references))
    <section class="info-panel authority-references" aria-labelledby="authority-references-heading">
        <span class="eyebrow">Primary sources</span><h2 id="authority-references-heading">Official References</h2>
        <ul>@foreach($references as $reference)<li><a href="{{ $reference['url'] }}" target="_blank" rel="noopener noreferrer">{{ $reference['name'] }} <span aria-hidden="true">↗</span></a></li>@endforeach</ul>
    </section>
@endif
