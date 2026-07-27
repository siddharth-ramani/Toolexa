<?php

namespace Tests\Feature;

use App\Services\BlogQualityService;
use App\Support\BlogRepository;
use Tests\TestCase;

class BlogQualityUpgradeEngineTest extends TestCase
{
    public function test_every_article_receives_unique_quality_content(): void
    {
        $service = app(BlogQualityService::class);
        $summaries = [];

        foreach (BlogRepository::all() as $article) {
            $quality = $service->build($article);

            $this->assertCount(5, $quality['summary'], $article['slug']);
            $this->assertGreaterThanOrEqual(300, str_word_count(implode(' ', $quality['introduction'])), $article['slug']);
            $this->assertGreaterThanOrEqual(8, count($quality['faqs']), $article['slug']);
            $this->assertLessThanOrEqual(12, count($quality['faqs']), $article['slug']);
            $this->assertNotEmpty($quality['steps'], $article['slug']);
            $this->assertNotEmpty($quality['mistakes'], $article['slug']);
            $summaries[] = sha1(json_encode($quality['summary']));
        }

        $this->assertCount(count($summaries), array_unique($summaries));
    }

    public function test_article_page_renders_quality_sections_and_schema(): void
    {
        $this->get('/blog/how-to-calculate-gst-in-india')
            ->assertOk()
            ->assertSee('Quick Summary')
            ->assertSee('Step-by-Step Guide')
            ->assertSee('Common Mistakes')
            ->assertSee('Official References')
            ->assertSee('Was this article helpful?')
            ->assertSee('Content Version')
            ->assertSee('Try Related Free Tools')
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('"@type":"Article"', false)
            ->assertSee('"@type":"Person"', false)
            ->assertSee('"@type":"Organization"', false);
    }

    public function test_manual_article_overrides_are_applied(): void
    {
        $article = BlogRepository::find('how-to-calculate-gst-in-india');
        $quality = app(BlogQualityService::class)->build($article);

        $this->assertSame('1.1', $quality['content_version']);
        $this->assertSame('GST Portal', $quality['references'][0]['name']);
    }
}
