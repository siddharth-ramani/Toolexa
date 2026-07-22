<article class="card tool-card workspace-tool-card">
    <span class="tool-icon">{{ $tool['icon'] }}</span>
    <h3><a href="{{ route('tools.show', $tool['slug']) }}">{{ $tool['name'] }}</a></h3>
    <p>{{ $tool['desc'] }}</p>
    <span class="tool-card-actions"><a class="btn btn-primary btn-sm" href="{{ route('tools.show', $tool['slug']) }}">{{ $buttonLabel ?? 'Open Tool' }}</a><a class="btn btn-sm" href="{{ route('workspace', ['add' => $tool['slug']]) }}">Open in Workspace</a></span>
</article>
