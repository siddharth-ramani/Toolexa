<?php

namespace Tests\Feature;

use App\Http\Controllers\Tools\HomeController;
use App\Services\CategoryLandingService;
use Tests\TestCase;

class CategoryLandingPageTest extends TestCase
{
    public function test_category_page_renders_complete_seo_landing_page(): void
    {
        $response = $this->get('/category/finance');

        $response->assertOk()
            ->assertSee('<h1>Finance Tools</h1>', false)
            ->assertSee('Featured Finance Tools')
            ->assertSee('Popular guides for this category')
            ->assertSee('Why use Toolexa?')
            ->assertSee('Finance Tools FAQs')
            ->assertSee('Related Categories')
            ->assertSee('FAQPage')
            ->assertSee('CollectionPage')
            ->assertSee('BreadcrumbList');
    }

    public function test_category_search_filters_tools_and_is_not_indexed(): void
    {
        $this->get('/category/finance?q=GST')
            ->assertOk()
            ->assertSee('GST Calculator')
            ->assertSee('noindex, follow', false);
    }

    public function test_category_editorial_copy_meets_requested_word_depth(): void
    {
        $category = collect(HomeController::categories())->firstWhere('slug', 'finance');
        $tools = collect(HomeController::tools())->where('category', $category['name'])->values()->all();
        $landing = app(CategoryLandingService::class)->landing($category, $tools);
        $wordCount = str_word_count(implode(' ', collect($landing['introduction'])->flatMap(fn (array $section) => $section['paragraphs'])->all()));

        $this->assertGreaterThanOrEqual(600, $wordCount);
        $this->assertLessThanOrEqual(1000, $wordCount);
        $this->assertCount(8, $landing['faqs']);
    }

    public function test_future_category_gets_a_complete_fallback_profile(): void
    {
        $landing = app(CategoryLandingService::class)->landing(['name' => 'Research Tools', 'slug' => 'research-tools'], []);

        $this->assertSame('Research Tools', $landing['label']);
        $this->assertCount(5, $landing['introduction']);
        $this->assertCount(8, $landing['faqs']);
    }
}
