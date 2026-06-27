<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    @foreach($breadcrumbs as $breadcrumb)
        @if($loop->last)
            <span>{{ $breadcrumb['name'] }}</span>
        @else
            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
        @endif
    @endforeach
</nav>
