@props(['subject'])

<div class="comparison-pros-cons">
    <section>
        <h3>Advantages of {{ $subject['name'] }}</h3>
        <ul>@foreach($subject['advantages'] as $item)<li>{{ $item }}</li>@endforeach</ul>
    </section>
    <section>
        <h3>Limitations of {{ $subject['name'] }}</h3>
        <ul>@foreach($subject['disadvantages'] as $item)<li>{{ $item }}</li>@endforeach</ul>
    </section>
</div>
