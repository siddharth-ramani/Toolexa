<?php

namespace Tests\Feature;

use Tests\TestCase;

class SiteDesignQualityTest extends TestCase
{
    public function test_unconfigured_ad_placements_do_not_render_fake_empty_boxes(): void
    {
        config()->set('services.google.adsense_slots', [
            'top' => null,
            'sidebar' => null,
            'inline' => null,
        ]);

        $this->get('/tools/gst-calculator')
            ->assertOk()
            ->assertDontSee('Top responsive ad')
            ->assertDontSee('Sidebar ad')
            ->assertDontSee('Calculator ad')
            ->assertDontSee('class="ad-slot', false);
    }

    public function test_configured_ad_placement_uses_responsive_adsense_markup(): void
    {
        config()->set('services.google.adsense_publisher_id', 'ca-pub-1234567890123456');
        config()->set('services.google.adsense_slots.inline', '1234567890');

        $this->get('/tools/gst-calculator')
            ->assertOk()
            ->assertSee('aria-label="Advertisement"', false)
            ->assertSee('data-ad-client="ca-pub-1234567890123456"', false)
            ->assertSee('data-ad-slot="1234567890"', false)
            ->assertSee('data-full-width-responsive="true"', false);
    }

    public function test_representative_page_families_keep_accessible_structure(): void
    {
        foreach ([
            '/',
            '/tools/gst-calculator',
            '/blog/how-to-calculate-gst-in-india',
            '/compare/jpg-vs-png',
            '/category/finance',
            '/finance-tools',
            '/trust',
        ] as $path) {
            $response = $this->get($path)->assertOk();
            $response->assertSee('href="#main-content"', false);
            $this->assertSame(1, substr_count($response->getContent(), '<h1'), $path);
            $this->assertStringNotContainsString('style="color: transparent', $response->getContent(), $path);
        }
    }

    public function test_shared_styles_include_mobile_navigation_focus_and_overflow_safeguards(): void
    {
        $css = file_get_contents(public_path('assets/css/style.css'));

        $this->assertStringContainsString('@media (max-width: 1180px)', $css);
        $this->assertStringContainsString(':focus-visible', $css);
        $this->assertStringContainsString('max-height: calc(100vh - 88px)', $css);
        $this->assertStringContainsString('.tool-hero .editorial-metadata', $css);
        $this->assertStringContainsString('.trust-hero :where(h1, h2)', $css);
    }

    public function test_age_calculator_has_a_clear_tool_specific_usage_guide(): void
    {
        $this->get('/tools/age-calculator')
            ->assertOk()
            ->assertSee('How to Use Age Calculator')
            ->assertSee('Select your date of birth')
            ->assertSee('Check the selected date')
            ->assertSee('Calculate your exact age')
            ->assertSee('Read the complete result')
            ->assertSee('The calculation uses today’s date.')
            ->assertSee('quality-step-number', false)
            ->assertSee('quality-step-note', false);
    }
}
