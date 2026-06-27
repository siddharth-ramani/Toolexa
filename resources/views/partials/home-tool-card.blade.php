<a class="card tool-card" href="{{ route('tools.show', $tool['slug']) }}">
    <span class="tool-icon">{{ $tool['icon'] }}</span>
    <h3>{{ $tool['name'] }}</h3>
    <p>{{ $tool['desc'] }}</p>
    <span class="btn btn-primary btn-sm">{{ $buttonLabel ?? 'Open Tool' }}</span>
</a>
