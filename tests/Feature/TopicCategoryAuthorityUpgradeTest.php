<?php

namespace Tests\Feature;

use App\Http\Controllers\Tools\HomeController;
use App\Services\AuthorityPageService;
use App\Services\CategoryLandingService;
use App\Services\TopicHubService;
use Tests\TestCase;

class TopicCategoryAuthorityUpgradeTest extends TestCase
{
    public function test_every_topic_has_unique_authority_content(): void
    {
        $hashes = [];

        foreach (array_keys(config('hubs.topics')) as $slug) {
            $topic = app(TopicHubService::class)->find($slug);
            $authority = $topic['authority'];
            $overviewWords = str_word_count(implode(' ', $authority['overview']));

            $this->assertGreaterThanOrEqual(300, $overviewWords, $slug);
            $this->assertLessThanOrEqual(500, $overviewWords, $slug);
            $this->assertGreaterThanOrEqual(10, count($authority['faqs']), $slug);
            $this->assertLessThanOrEqual(15, count($authority['faqs']), $slug);
            $this->assertGreaterThanOrEqual(6, count($authority['glossary']), $slug);
            $this->assertNotEmpty($authority['use_cases'], $slug);
            $this->assertNotEmpty($authority['best_practices'], $slug);
            $this->assertNotEmpty($authority['mistakes'], $slug);
            $hashes[] = sha1(json_encode($authority['overview']));
        }

        $this->assertCount(count($hashes), array_unique($hashes));
    }

    public function test_topic_page_renders_complete_authority_structure(): void
    {
        $this->get('/image-tools')
            ->assertOk()
            ->assertSee('Quick Overview')
            ->assertSee('Last Updated')
            ->assertSee('Featured Image Tools')
            ->assertSee('Search')
            ->assertSee('Sort')
            ->assertSee('Filter')
            ->assertSee('Featured Image Tools Comparisons')
            ->assertSee('Featured Image Tools Articles')
            ->assertSee('Common Use Cases')
            ->assertSee('Official References')
            ->assertSee('Was this topic helpful?')
            ->assertSee('Written by')
            ->assertSee('Share this topic')
            ->assertSee('Explore More Tools')
            ->assertSee('"@type":"ItemList"', false)
            ->assertSee('"@type":"Organization"', false);
    }

    public function test_category_pages_receive_the_same_authority_layer(): void
    {
        foreach (HomeController::categories() as $category) {
            $response = $this->get('/category/'.$category['slug'])->assertOk();
            $response->assertSee('Quick Overview')->assertSee('Common Use Cases')->assertSee('Content details');
        }
    }

    public function test_filters_work_and_are_not_indexed(): void
    {
        $this->get('/image-tools?q=compress&sort=name&filter=all')
            ->assertOk()
            ->assertSee('Image Compressor')
            ->assertSee('noindex, follow', false);

        $this->get('/category/finance?filter=featured')
            ->assertOk()
            ->assertSee('noindex, follow', false);
    }

    public function test_future_category_uses_a_complete_unique_fallback(): void
    {
        $landing = app(CategoryLandingService::class)->landing(['name' => 'Research Tools', 'slug' => 'research-tools'], []);
        $authority = app(AuthorityPageService::class)->build('category-research-tools', array_merge($landing, ['title' => $landing['label']]), [], [], []);

        $this->assertGreaterThanOrEqual(300, str_word_count(implode(' ', $authority['overview'])));
        $this->assertGreaterThanOrEqual(10, count($authority['faqs']));
        $this->assertNotEmpty($authority['glossary']);
    }

    public function test_topic_index_has_collection_and_item_list_schema(): void
    {
        $this->get('/topics')
            ->assertOk()
            ->assertSee('Authority Topics')
            ->assertSee('"@type":"CollectionPage"', false)
            ->assertSee('"@type":"ItemList"', false);
    }
}
