@props(['formula'])
<section class="info-panel tool-quality-section" aria-labelledby="tool-formula-heading">
    <span class="eyebrow">Calculation method</span>
    <h2 id="tool-formula-heading">{{ $formula['title'] }}</h2>
    <div class="quality-formulas">@foreach($formula['items'] as $item)<code>{{ $item }}</code>@endforeach</div>
    <p>{{ $formula['explanation'] }}</p>
    @if($formula['variables'])
        <h3>Formula variables</h3>
        <dl class="quality-variable-list">
            @foreach($formula['variables'] as $variable)<div><dt>{{ $variable['symbol'] }}</dt><dd>{{ $variable['meaning'] }}</dd></div>@endforeach
        </dl>
    @endif
</section>
