<?php

namespace Tests\Feature;

use App\Services\TopicHubService;
use Tests\TestCase;

class TopicHubTest extends TestCase
{
    private array $slugs = ['image-tools', 'pdf-tools', 'finance-tools', 'developer-tools', 'seo-tools', 'text-tools'];

    public function test_every_configured_hub_renders_authority_page_sections(): void
    {
        foreach ($this->slugs as $slug) {
            $this->get('/'.$slug)
                ->assertOk()
                ->assertSee('Beginner')
                ->assertSee('Guide to')
                ->assertSee('Featured')
                ->assertSee('Complete directory')
                ->assertSee('Comparisons')
                ->assertSee('Popular')
                ->assertSee('Best Practices')
                ->assertSee('Common Mistakes')
                ->assertSee('Glossary')
                ->assertSee('Related Topic Hubs')
                ->assertSee('CollectionPage')
                ->assertSee('FAQPage')
                ->assertSee('BreadcrumbList');
        }
    }

    public function test_beginner_guides_meet_requested_word_range(): void
    {
        $service = app(TopicHubService::class);

        foreach ($this->slugs as $slug) {
            $hub = $service->find($slug);
            $text = collect($hub['guide'])->flatMap(fn (array $section) => $section['paragraphs'])->implode(' ');
            $words = str_word_count($text);

            $this->assertGreaterThanOrEqual(1000, $words, $slug.' guide is too short.');
            $this->assertLessThanOrEqual(1500, $words, $slug.' guide is too long.');
        }
    }

    public function test_hub_counts_and_content_are_generated_from_live_sources(): void
    {
        $hub = app(TopicHubService::class)->find('image-tools');

        $this->assertSame(count($hub['tools']), $hub['tool_count']);
        $this->assertSame(count($hub['comparisons']), $hub['comparison_count']);
        $this->assertNotEmpty($hub['featured_tools']);
        $this->assertNotEmpty($hub['articles']);
        $this->assertCount(8, $hub['faqs']);
        $this->assertCount(3, $hub['related_hubs']);
    }

    public function test_topic_index_and_sitemap_prevent_orphan_hubs(): void
    {
        $index = $this->get('/topics')->assertOk();
        $sitemap = $this->get('/sitemap.xml')->assertOk();

        foreach ($this->slugs as $slug) {
            $url = route('hub.show', $slug);
            $index->assertSee($url, false);
            $sitemap->assertSee($url, false);
        }
    }

    public function test_unknown_hub_is_not_resolved(): void
    {
        $this->assertNull(app(TopicHubService::class)->find('unknown-tools'));
    }
}
