@props(['category'])

<a class="premium-category-card" href="{{ route('category.show', $category['slug']) }}">
    <span class="tool-icon" aria-hidden="true">{{ $category['icon'] }}</span>
    <span>
        <strong>{{ str_contains($category['name'], 'Tools') ? $category['name'] : $category['name'].' Tools' }}</strong>
        <small>{{ $category['count'] }} tools</small>
        <p>{{ $category['description'] }}</p>
        <b>Open Category →</b>
    </span>
</a>
