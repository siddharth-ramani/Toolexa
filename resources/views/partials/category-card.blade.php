@php
    $isAvailable = empty($category['status']) && $category['count'] > 0;
@endphp

@if($isAvailable)
    <a class="category-card" href="{{ route('category.show', $category['slug']) }}">
        <span class="tool-icon">{{ $category['icon'] }}</span>
        <strong>{{ $category['name'] }}</strong>
        <small>{{ $category['count'] }} tools</small>
    </a>
@else
    <div class="category-card is-disabled">
        <span class="tool-icon">{{ $category['icon'] }}</span>
        <strong>{{ $category['name'] }}</strong>
        <small>{{ $category['status'] ?? 'Coming Soon' }}</small>
    </div>
@endif
