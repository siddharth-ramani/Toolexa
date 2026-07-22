<?php

namespace Tests\Feature;

use App\Services\ComparisonService;
use Tests\TestCase;

class ComparisonEngineTest extends TestCase
{
    public function test_configured_comparison_pages_render_complete_structure(): void
    {
        foreach (['jpg-vs-png', 'webp-vs-png', 'fd-vs-sip', 'json-vs-xml'] as $slug) {
            $this->get('/compare/'.$slug)
                ->assertOk()
                ->assertSee('Quick Winner')
                ->assertSee('Feature Comparison')
                ->assertSee('Common Mistakes')
                ->assertSee('Tools for this comparison')
                ->assertSee('FAQPage')
                ->assertSee('BreadcrumbList')
                ->assertSee('Article');
        }
    }

    public function test_comparison_index_prevents_orphan_pages(): void
    {
        $response = $this->get('/compare')->assertOk();

        foreach (config('comparisons.pairs') as $pair) {
            $response->assertSee(route('compare.show', $pair['left'].'-vs-'.$pair['right']), false);
        }
    }

    public function test_comparison_service_builds_dynamic_rows_and_faqs(): void
    {
        $comparison = app(ComparisonService::class)->find('jpg-vs-png');

        $this->assertSame('JPG vs PNG', $comparison['title']);
        $this->assertGreaterThanOrEqual(8, count($comparison['rows']));
        $this->assertCount(4, $comparison['winners']);
        $this->assertCount(8, $comparison['faqs']);
        $this->assertNotEmpty($comparison['related_tools']);
        $this->assertNotEmpty($comparison['related_articles']);
    }

    public function test_new_pair_can_be_enabled_without_layout_changes(): void
    {
        config()->set('comparisons.pairs', [['left' => 'png', 'right' => 'webp']]);

        $this->get('/compare/png-vs-webp')
            ->assertOk()
            ->assertSee('<h1>PNG vs WebP</h1>', false);
    }

    public function test_comparisons_are_in_sitemap(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(route('compare.index'), false)
            ->assertSee(route('compare.show', 'jpg-vs-png'), false);
    }

    public function test_unconfigured_comparison_returns_not_found(): void
    {
        $this->get('/compare/unknown-vs-missing')->assertNotFound();
    }
}
