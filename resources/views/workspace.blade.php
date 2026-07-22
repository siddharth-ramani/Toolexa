@extends('layouts.app')

@section('content')
    <div class="tool-workspace" data-tool-workspace data-add-tool="{{ request()->query('add', '') }}">
        <header class="workspace-header">
            <div><span class="eyebrow">Private local workspace</span><h1>Multi-Tool Workspace</h1><p>Use up to four Toolexa tools side by side. Your panels and saved workspaces stay in this browser.</p></div>
            <a class="btn" href="{{ route('dashboard') }}">My Dashboard</a>
        </header>

        <section class="workspace-toolbar" aria-label="Workspace controls">
            <form data-workspace-add-form>
                <label class="sr-only" for="workspace-tool-select">Add a tool</label>
                <select id="workspace-tool-select" class="form-control" data-workspace-tool-select>
                    <option value="">Choose a tool to add</option>
                    @foreach($workspaceTools as $tool)<option value="{{ $tool['slug'] }}">{{ $tool['name'] }} · {{ $tool['category'] }}</option>@endforeach
                </select>
                <button class="btn btn-primary" type="submit">Add Tool</button>
            </form>
            <form data-workspace-save-form>
                <label class="sr-only" for="workspace-name">Workspace name</label>
                <input id="workspace-name" class="form-control" type="text" maxlength="60" placeholder="Save as Developer" required>
                <button class="btn" type="submit">Save Workspace</button>
            </form>
            <span class="workspace-limit" data-workspace-limit>0 of 4 tools</span>
        </section>

        <section class="workspace-switcher" aria-labelledby="quick-switch-heading">
            <div><span class="eyebrow">Saved layouts</span><h2 id="quick-switch-heading">Quick Switch</h2></div>
            <div data-workspace-switches></div>
        </section>

        <p class="workspace-status" data-workspace-status aria-live="polite"></p>
        <section class="workspace-canvas" data-workspace-canvas aria-label="Open tool panels"></section>

        <template data-workspace-empty-template>
            <div class="workspace-empty"><span class="tool-icon">+</span><h2>Build your workspace</h2><p>Add up to four tools using the selector above, or open a tool with its “Open in Workspace” action.</p></div>
        </template>

        <script type="application/json" data-workspace-catalog>@json($workspaceTools, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
    </div>
@endsection
