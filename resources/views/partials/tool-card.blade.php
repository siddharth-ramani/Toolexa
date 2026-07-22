<article class="mini-tool-card-wrap"><a class="mini-tool-card" href="{{ url('tools/'.$tool['slug']) }}" @if($newTab ?? false) target="_blank" rel="noopener" @endif>
    <span class="tool-icon">{{ $tool['icon'] }}</span>
    <span>
        <strong>{{ $tool['name'] }}</strong>
        <small>{{ $tool['desc'] ?? $tool['category'] }}</small>
    </span>
</a><a class="mini-workspace-link" href="{{ route('workspace', ['add' => $tool['slug']]) }}" aria-label="Open {{ $tool['name'] }} in workspace">Workspace</a></article>
