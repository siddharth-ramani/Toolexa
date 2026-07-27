@props(['date'])
<span class="editorial-badge">Updated <time datetime="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('F Y') }}</time></span>
