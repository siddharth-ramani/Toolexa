<?php

namespace Tests\Feature;

use App\Http\Controllers\Tools\HomeController;
use App\Services\ToolPageQualityService;
use Tests\TestCase;

class ToolPageQualityEngineTest extends TestCase
{
    public function test_every_tool_receives_substantive_unique_quality_content(): void
    {
        $engine = app(ToolPageQualityService::class);
        $introductions = [];

        foreach (HomeController::tools() as $tool) {
            $quality = $engine->build($tool);
            $introWords = str_word_count($quality['introduction']);
            $detailWords = str_word_count(implode(' ', $quality['detailed_explanation']));
            $manuallyEdited = (bool) config('tool_quality.overrides.'.$tool['slug'].'.manually_edited', false);

            if ($manuallyEdited) {
                $this->assertGreaterThanOrEqual(100, $introWords, $tool['slug'].' manual introduction lacks context');
                $this->assertGreaterThanOrEqual(150, $detailWords, $tool['slug'].' manual explanation lacks useful detail');
            } else {
                $this->assertGreaterThanOrEqual(150, $introWords, $tool['slug'].' introduction is too short');
                $this->assertLessThanOrEqual(250, $introWords, $tool['slug'].' introduction is too long');
                $this->assertGreaterThanOrEqual(400, $detailWords, $tool['slug'].' explanation is too short');
                $this->assertLessThanOrEqual(800, $detailWords, $tool['slug'].' explanation is too long');
            }
            $this->assertGreaterThanOrEqual(3, count($quality['examples']));
            $this->assertLessThanOrEqual(5, count($quality['examples']));
            $this->assertGreaterThanOrEqual(5, count($quality['advantages']));
            $this->assertLessThanOrEqual(10, count($quality['advantages']));
            $this->assertGreaterThanOrEqual(5, count($quality['limitations']));
            $this->assertGreaterThanOrEqual(5, count($quality['mistakes']));
            $this->assertGreaterThanOrEqual(5, count($quality['best_practices']));
            $this->assertGreaterThanOrEqual(8, count($quality['faqs']));
            $this->assertLessThanOrEqual(12, count($quality['faqs']));
            $this->assertGreaterThan(0, $quality['reading_time']);
            $introductions[] = $quality['introduction'];
        }

        $this->assertCount(count(HomeController::tools()), array_unique($introductions));
    }

    public function test_tool_page_follows_quality_section_order(): void
    {
        $html = $this->get('/tools/gst-calculator')->assertOk()->getContent();
        $markers = [
            'GST Calculator</h1>',
            'tool-quality-intro',
            'name="amount"',
            'How to Use GST Calculator',
            'GST Calculator Examples',
            'Calculation method',
            'How GST Calculator Works',
            'Advantages of Using GST Calculator',
            'Limitations to Understand',
            'Common Mistakes',
            'Best Practices',
            'GST Calculator FAQs',
            'Related Tools',
            'Learn more about this tool',
            'Official References',
            'Page Details',
            'Was this page helpful?',
            'Explore More Free Tools',
        ];
        $positions = array_map(fn (string $marker) => strpos($html, $marker), $markers);

        foreach ($positions as $position) {
            $this->assertNotFalse($position);
        }
        $this->assertSame($positions, collect($positions)->sort()->values()->all());
    }

    public function test_formula_references_and_comparisons_are_conditional(): void
    {
        $this->get('/tools/gst-calculator')
            ->assertOk()
            ->assertSee('GST Amount = Amount x GST Rate / 100')
            ->assertSee('https://www.gst.gov.in/', false);

        $this->get('/tools/json-formatter')
            ->assertOk()
            ->assertDontSee('Calculation method')
            ->assertSee('https://developer.mozilla.org/', false);

        $this->get('/tools/age-calculator')
            ->assertOk()
            ->assertDontSee('Official References');
    }

    public function test_quality_schema_and_local_feedback_are_present(): void
    {
        $response = $this->get('/tools/percentage-calculator')->assertOk();
        $html = $response->getContent();

        $response
            ->assertSee('"@type":"SoftwareApplication"', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('data-tool-feedback', false)
            ->assertSee('aria-pressed="false"', false);

        $this->assertGreaterThanOrEqual(8, substr_count($html, '"@type":"Question"'));
        $script = file_get_contents(public_path('assets/js/app.js'));
        $this->assertStringContainsString('toolexa_tool_feedback_', $script);
        $this->assertStringContainsString('window.localStorage.setItem', $script);
    }

    public function test_future_tool_uses_same_engine_without_template_changes(): void
    {
        $future = [
            'slug' => 'future-data-checker',
            'name' => 'Future Data Checker',
            'desc' => 'Validate structured data before publishing.',
            'category' => 'Developer Tools',
            'how_to' => ['Paste the structured data.', 'Choose a validation mode.', 'Run the check.'],
            'features' => ['Browser based', 'Clear validation output', 'No signup required'],
            'faq' => [],
        ];
        $quality = app(ToolPageQualityService::class)->build($future);

        $this->assertGreaterThanOrEqual(150, str_word_count($quality['introduction']));
        $this->assertGreaterThanOrEqual(400, str_word_count(implode(' ', $quality['detailed_explanation'])));
        $this->assertGreaterThanOrEqual(8, count($quality['faqs']));
        $this->assertNotEmpty($quality['references']);
        $this->assertNull($quality['formula']);
    }

    public function test_priority_tools_use_manually_edited_specific_content(): void
    {
        $image = $this->get('/tools/image-compressor')->assertOk();
        $image
            ->assertSee('browser PNG encoders may ignore it')
            ->assertSee('The output remains PNG with the same dimensions and transparency')
            ->assertSee('Canvas re-encoding can discard EXIF metadata')
            ->assertDontSee('Quick everyday check')
            ->assertDontSee('Compare two scenarios');

        $meesho = $this->get('/tools/meesho-label-cropper')->assertOk();
        $meesho
            ->assertSee('eight cropped labels are arranged four per A4 sheet')
            ->assertSee('PNG export creates an image from the current preview page only')
            ->assertSee('browser limits processing to 300 PDF pages')
            ->assertDontSee('Quick everyday check')
            ->assertDontSee('Choose any unit, rate, format or processing option');

        $imageScript = file_get_contents(public_path('assets/js/app.js'));
        $this->assertStringContainsString('makeCanvas(sourceImage.naturalWidth, sourceImage.naturalHeight)', $imageScript);
        $this->assertStringContainsString("sourceFile.type === 'image/png' ? 'image/png' : 'image/jpeg'", $imageScript);

        $cropScript = file_get_contents(public_path('assets/js/seller-crop-engine.js'));
        $this->assertStringContainsString('var MAX_PAGES = 300;', $cropScript);
        $this->assertStringContainsString("outputLayout === 'a4_grid'", $cropScript);
        $this->assertStringContainsString("setStatus('PNG downloaded (current page only, high resolution).')", $cropScript);
    }
}
