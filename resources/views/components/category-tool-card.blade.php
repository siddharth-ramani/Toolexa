@props(['tool', 'badge' => null])

<article class="card tool-card category-tool-card">
    <span class="tool-icon" aria-hidden="true">{{ $tool['icon'] }}</span>
    <span class="category-tool-body">
        @if($badge)<span class="category-card-badge">{{ $badge }}</span>@endif
        <h3><a href="{{ url('tools/'.$tool['slug']) }}">{{ $tool['name'] }}</a></h3>
        <p>{{ $tool['desc'] }}</p>
        <small>{{ $tool['category'] }}</small>
        <span class="tool-card-actions"><a class="btn btn-primary btn-sm" href="{{ url('tools/'.$tool['slug']) }}" aria-label="Open {{ $tool['name'] }}">Open Tool</a><a class="btn btn-sm" href="{{ route('workspace', ['add' => $tool['slug']]) }}">Workspace</a></span>
    </span>
</article>
