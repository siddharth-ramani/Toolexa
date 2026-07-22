<?php

namespace Tests\Feature;

use Tests\TestCase;

class PerformanceOptimizationTest extends TestCase
{
    public function test_global_assets_are_deferred_minified_and_route_split(): void
    {
        $home = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('/assets/css/style.min.css', $home);
        $this->assertStringContainsString('/assets/js/app.min.js" defer', $home);
        $this->assertStringNotContainsString('/assets/js/workspace.min.js', $home);
        $this->assertStringNotContainsString('/assets/js/dashboard.min.js', $home);

        $this->get('/dashboard')->assertOk()->assertSee('/assets/js/dashboard.min.js" defer', false);
        $this->get('/workspace')->assertOk()->assertSee('/assets/js/workspace.min.js" defer', false);
    }

    public function test_critical_images_reserve_space_and_have_modern_fallback(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('logo.webp', false)
            ->assertSee('width="160" height="48"', false)
            ->assertSee('fetchpriority="high"', false);

        $this->assertFileExists(public_path('assets/images/logo.webp'));
    }

    public function test_precompressed_production_assets_exist(): void
    {
        foreach (['css/style.min.css', 'css/bootstrap-lite.min.css', 'js/app.min.js'] as $asset) {
            $this->assertFileExists(public_path('assets/'.$asset));
            $this->assertFileExists(public_path('assets/'.$asset.'.br'));
            $this->assertFileExists(public_path('assets/'.$asset.'.gz'));
        }
    }

    public function test_sitemap_uses_shared_cache_headers(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=21600, public, stale-while-revalidate=86400');
    }
}
