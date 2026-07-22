@props(['item'])

<a class="smart-search-card" href="{{ $item['url'] }}" data-search-option role="option" tabindex="-1">
    <span class="tool-icon" aria-hidden="true">{{ $item['icon'] }}</span>
    <span class="smart-search-card-body">
        <strong>{{ $item['title'] }}</strong>
        <span>{{ $item['category'] }}</span>
        <small>{{ $item['description'] }}</small>
    </span>
    <span class="btn btn-primary btn-sm">Open</span>
</a>
