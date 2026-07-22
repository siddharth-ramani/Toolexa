<?php

namespace Tests\Feature;

use Tests\TestCase;

class PersonalDashboardTest extends TestCase
{
    public function test_dashboard_renders_every_personalization_section(): void
    {
        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Welcome Back')
            ->assertSee('Recently Used Tools')
            ->assertSee('Favorite Tools')
            ->assertSee('Favorite Articles')
            ->assertSee('Favorite Comparisons')
            ->assertSee('Pinned Tools')
            ->assertSee('Custom Collections')
            ->assertSee('Continue Reading')
            ->assertSee('Recent Searches')
            ->assertSee('Export JSON')
            ->assertSee('Import JSON')
            ->assertSee('Reset Dashboard')
            ->assertSee('noindex, follow', false)
            ->assertSee('data-dashboard-catalog', false);
    }

    public function test_trackable_pages_expose_safe_dashboard_metadata(): void
    {
        $this->get('/tools/gst-calculator')
            ->assertOk()
            ->assertSee('data-dashboard-item=', false)
            ->assertSee('&quot;type&quot;:&quot;tool&quot;', false);

        $this->get('/blog/how-to-calculate-gst-in-india')
            ->assertOk()
            ->assertSee('&quot;type&quot;:&quot;article&quot;', false);

        $this->get('/compare/jpg-vs-png')
            ->assertOk()
            ->assertSee('&quot;type&quot;:&quot;comparison&quot;', false);
    }

    public function test_storage_manager_loads_before_main_application_script(): void
    {
        $response = $this->get('/dashboard')->assertOk();
        $html = $response->getContent();

        $this->assertLessThan(strpos($html, '/assets/js/app.min.js'), strpos($html, '/assets/js/dashboard.min.js'));
        $this->assertStringContainsString('toolexa_dashboard_v1', file_get_contents(public_path('assets/js/dashboard.js')));
        $this->assertStringContainsString('MAX_RECENT = 20', file_get_contents(public_path('assets/js/dashboard.js')));
        $this->assertStringContainsString('window.localStorage', file_get_contents(public_path('assets/js/dashboard.js')));
    }

    public function test_dashboard_is_linked_from_global_navigation(): void
    {
        $this->get('/')->assertOk()->assertSee(route('dashboard'), false)->assertSee('My Dashboard');
    }
}
