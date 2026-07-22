<?php

namespace Tests\Feature;

use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    public function test_workspace_renders_local_multi_tool_interface(): void
    {
        $this->get('/workspace')
            ->assertOk()
            ->assertSee('Multi-Tool Workspace')
            ->assertSee('Quick Switch')
            ->assertSee('Save Workspace')
            ->assertSee('0 of 4 tools')
            ->assertSee('data-workspace-catalog', false)
            ->assertSee('noindex, follow', false);
    }

    public function test_tool_can_render_in_clean_workspace_embed_mode(): void
    {
        $this->get('/tools/json-formatter?workspace=1')
            ->assertOk()
            ->assertSee('JSON Formatter')
            ->assertSee('workspace-embedded-page', false)
            ->assertDontSee('class="site-nav"', false)
            ->assertDontSee('class="site-footer"', false);
    }

    public function test_tool_surfaces_link_to_workspace(): void
    {
        $workspaceUrl = route('workspace', ['add' => 'gst-calculator']);

        $this->get('/')->assertOk()->assertSee($workspaceUrl, false);
        $this->get('/tools/gst-calculator')->assertOk()->assertSee($workspaceUrl, false);
        $this->get('/category/finance')->assertOk()->assertSee('Workspace');
    }

    public function test_workspace_manager_is_local_only_and_loaded_before_application(): void
    {
        $html = $this->get('/workspace')->assertOk()->getContent();
        $script = file_get_contents(public_path('assets/js/workspace.js'));

        $this->assertLessThan(strpos($html, '/assets/js/app.min.js'), strpos($html, '/assets/js/workspace.min.js'));
        $this->assertStringContainsString("toolexa_workspaces_v1", $script);
        $this->assertStringContainsString('MAX_PANELS = 4', $script);
        $this->assertStringContainsString('localStorage', $script);
        $this->assertStringContainsString('ResizeObserver', $script);
        $this->assertStringContainsString('pointerdown', $script);
    }
}
