<?php

namespace Tests\Feature;

use App\Services\ComparisonQualityService;
use App\Services\ComparisonService;
use Tests\TestCase;

class ComparisonPageQualityUpgradeEngineTest extends TestCase
{
    public function test_every_comparison_receives_unique_decision_content(): void
    {
        $catalog = app(ComparisonService::class)->all();
        $qualityService = app(ComparisonQualityService::class);
        $summaries = [];

        foreach ($catalog as $comparison) {
            $quality = $qualityService->build($comparison, $catalog);

            $this->assertCount(5, $quality['recommendations'], $comparison['slug']);
            $this->assertGreaterThanOrEqual(10, count($quality['faqs']), $comparison['slug']);
            $this->assertLessThanOrEqual(15, count($quality['faqs']), $comparison['slug']);
            $this->assertNotEmpty($quality['examples'], $comparison['slug']);
            $this->assertNotEmpty($quality['decisions']['left']['choose_if'], $comparison['slug']);
            $this->assertNotEmpty($quality['decisions']['right']['choose_if'], $comparison['slug']);
            $this->assertGreaterThan(0, $quality['word_count'], $comparison['slug']);
            $this->assertGreaterThan(0, $quality['reading_time'], $comparison['slug']);
            $summaries[] = sha1(json_encode($quality['summary']));
        }

        $this->assertCount(count($summaries), array_unique($summaries));
    }

    public function test_comparison_page_renders_complete_quality_structure(): void
    {
        $this->get('/compare/jpg-vs-png')
            ->assertOk()
            ->assertSee('Quick Recommendation')
            ->assertSee('Quick Summary')
            ->assertSee('Feature Comparison')
            ->assertSee('History and background')
            ->assertSee('Advantages of JPG')
            ->assertSee('Limitations of PNG')
            ->assertSee('Real-World Examples')
            ->assertSee('Interactive decision checklist')
            ->assertSee('Related Comparisons')
            ->assertSee('Official References')
            ->assertSee('Was this comparison helpful?')
            ->assertSee('Content Version')
            ->assertSee('Areas of Expertise')
            ->assertSee('View More Comparisons')
            ->assertSee('<meta property="og:type" content="article">', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('"@type":"Person"', false);
    }

    public function test_manual_override_and_local_feedback_are_enabled(): void
    {
        $catalog = app(ComparisonService::class)->all();
        $comparison = collect($catalog)->firstWhere('slug', 'jpg-vs-png');
        $quality = app(ComparisonQualityService::class)->build($comparison, $catalog);

        $this->assertSame('1.1', $quality['content_version']);
        $this->assertSame('Product photography', $quality['examples'][0]['scenario']);

        $this->get('/compare/jpg-vs-png')
            ->assertSee('data-comparison-feedback="jpg-vs-png"', false)
            ->assertSee('data-comparison-vote="yes"', false);
    }

    public function test_future_pair_uses_the_same_quality_engine(): void
    {
        config()->set('comparisons.pairs', [['left' => 'png', 'right' => 'webp']]);

        $this->get('/compare/png-vs-webp')
            ->assertOk()
            ->assertSee('Quick Recommendation')
            ->assertSee('Real-World Examples')
            ->assertSee('Content Version');
    }
}
