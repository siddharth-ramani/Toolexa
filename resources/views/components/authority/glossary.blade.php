@props(['title', 'terms'])

<section class="info-panel hub-glossary" aria-labelledby="authority-glossary-heading">
    <span class="eyebrow">Essential terminology</span>
    <h2 id="authority-glossary-heading">{{ $title }} Glossary</h2>
    <dl>@foreach($terms as $term => $definition)<div><dt>{{ $term }}</dt><dd>{{ $definition }}</dd></div>@endforeach</dl>
</section>
