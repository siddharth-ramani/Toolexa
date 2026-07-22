<?php

namespace Tests\Feature;

use App\Http\Controllers\Tools\HomeController;
use App\Services\HomepageService;
use App\Support\BlogRepository;
use Tests\TestCase;

class PremiumHomepageTest extends TestCase
{
    public function test_homepage_renders_all_conversion_sections_and_schemas(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('100+ Free Online Tools &amp; Calculators', false)
            ->assertSee('Featured Categories')
            ->assertSee('Trending Tools')
            ->assertSee('Recently Added')
            ->assertSee('Featured Articles')
            ->assertSee("Editor's Picks")
            ->assertSee('Why Choose Toolexa?')
            ->assertSee('Popular Collections')
            ->assertSee('Get notified when new free tools are added.')
            ->assertSee('Find the right tool in seconds.')
            ->assertSee('FAQPage')
            ->assertSee('CollectionPage')
            ->assertSee('SearchAction');
    }

    public function test_homepage_data_tracks_live_catalogs(): void
    {
        $data = app(HomepageService::class)->data();

        $this->assertSame(count(HomeController::tools()), $data['toolCount']);
        $this->assertSame(count(BlogRepository::all()), $data['articleCount']);
        $this->assertCount(8, $data['trendingTools']);
        $this->assertCount(8, $data['recentTools']);
        $this->assertCount(6, $data['featuredArticles']);
        $this->assertCount(6, $data['editorPicks']);
    }

    public function test_editorial_tool_selections_are_configurable(): void
    {
        config()->set('homepage.editor_picks', ['gst-calculator', 'emi-calculator']);
        $picks = app(HomepageService::class)->data()['editorPicks'];

        $this->assertSame('gst-calculator', $picks[0]['slug']);
        $this->assertSame('emi-calculator', $picks[1]['slug']);
    }
}
