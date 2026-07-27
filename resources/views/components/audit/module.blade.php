@props(['name', 'score', 'checks'])

<section class="audit-module">
    <header><strong>{{ \Illuminate\Support\Str::headline($name) }}</strong><span>{{ $score }}%</span></header>
    <div class="audit-progress" role="progressbar" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ \Illuminate\Support\Str::headline($name) }} score"><i style="width: {{ $score }}%"></i></div>
    <ul>
        @foreach($checks as $check)
            <li class="{{ $check['pass'] ? 'audit-pass' : 'audit-fail' }}"><span>{{ $check['pass'] ? '✓' : '!' }}</span><div><strong>{{ $check['label'] }}</strong>@if($check['detail'])<small>{{ $check['detail'] }}</small>@endif</div></li>
        @endforeach
    </ul>
</section>
