<?php

namespace Tests\Feature;

use App\Services\IntelligentSearchService;
use Tests\TestCase;

class IntelligentSearchTest extends TestCase
{
    public function test_fuzzy_search_handles_common_typing_errors(): void
    {
        $service = app(IntelligentSearchService::class);

        $passwordResults = $service->search('passwrod');
        $jsonResults = $service->search('json formater');

        $this->assertContains('password-generator', array_column($passwordResults['groups']['tools'], 'slug'));
        $this->assertContains('json-formatter', array_column($jsonResults['groups']['tools'], 'slug'));
    }

    public function test_search_returns_grouped_tools_articles_and_categories(): void
    {
        $results = app(IntelligentSearchService::class)->search('finance');

        $this->assertArrayHasKey('tools', $results['groups']);
        $this->assertArrayHasKey('articles', $results['groups']);
        $this->assertArrayHasKey('categories', $results['groups']);
        $this->assertNotEmpty($results['groups']['categories']);
    }

    public function test_api_supports_type_filters(): void
    {
        $response = $this->getJson('/api/search?q=gst&filter=articles');

        $response->assertOk()
            ->assertJsonPath('filter', 'articles')
            ->assertJsonCount(0, 'groups.tools')
            ->assertJsonCount(0, 'groups.categories')
            ->assertHeader('Cache-Control', 'max-age=300, public');
    }

    public function test_search_page_is_accessible_and_result_queries_are_not_indexed(): void
    {
        $this->get('/search')
            ->assertOk()
            ->assertSee('data-smart-search', false)
            ->assertSee('aria-controls="smart-search-results"', false)
            ->assertSee('Trending searches');

        $this->get('/search?q=gst')
            ->assertOk()
            ->assertSee('noindex, follow', false)
            ->assertSee('GST Calculator');
    }

    public function test_one_character_query_is_supported(): void
    {
        $this->getJson('/api/search?q=g')->assertOk()->assertJsonPath('query', 'g');
    }
}
