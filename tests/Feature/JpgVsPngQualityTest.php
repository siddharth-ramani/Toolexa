<?php

namespace Tests\Feature;

use Tests\TestCase;

class JpgVsPngQualityTest extends TestCase
{
    public function test_comparison_uses_specific_search_metadata_and_explanation(): void
    {
        $this->get('/compare/jpg-vs-png')
            ->assertOk()
            ->assertSee('<title>JPG vs PNG: Quality, File Size &amp; Transparency Compared</title>', false)
            ->assertSee('JPG normally uses lossy compression', false)
            ->assertSee('PNG uses lossless compression', false)
            ->assertDontSee('Code 128');
    }

    public function test_comparison_covers_real_format_decisions_and_limitations(): void
    {
        $this->get('/compare/jpg-vs-png')
            ->assertOk()
            ->assertSee('Best for Photographs')
            ->assertSee('Best for Transparency')
            ->assertSee('Converting an already compressed JPG to PNG and expecting lost detail to return.')
            ->assertDontSee('4006381333931')
            ->assertSee('PNG Specification, Third Edition')
            ->assertSee('JPEG 1 standard overview')
            ->assertSee('October 2026');
    }

    public function test_comparison_faq_schema_uses_specific_questions(): void
    {
        $html = $this->get('/compare/jpg-vs-png')->assertOk()->getContent();

        $this->assertStringContainsString('Does converting JPG to PNG improve quality?', $html);
        $this->assertStringContainsString('Is opening a JPG harmful to quality?', $html);
        $this->assertStringContainsString('FAQPage', $html);
        $this->assertGreaterThanOrEqual(10, substr_count($html, '<details'));
    }
}
