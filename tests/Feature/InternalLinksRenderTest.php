<?php

namespace Tests\Feature;

use App\Support\BlogRepository;
use Tests\TestCase;

class InternalLinksRenderTest extends TestCase
{
    public function test_tool_page_renders_smart_tool_and_article_links(): void
    {
        $this->get('/tools/gst-calculator')
            ->assertOk()
            ->assertSee('Related Tools')
            ->assertSee('Learn more about this tool');
    }

    public function test_article_page_renders_links_back_to_tools(): void
    {
        $article = BlogRepository::all()[0];

        $this->get('/blog/'.$article['slug'])
            ->assertOk()
            ->assertSee('Try these Toolexa tools')
            ->assertSee('Keep Reading');
    }
}
