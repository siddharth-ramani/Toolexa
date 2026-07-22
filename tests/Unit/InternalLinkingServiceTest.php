<?php

namespace Tests\Unit;

use App\Services\InternalLinkingService;
use Tests\TestCase;

class InternalLinkingServiceTest extends TestCase
{
    private array $tools = [
        ['name' => 'GST Calculator', 'slug' => 'gst-calculator', 'category' => 'Finance', 'keywords' => 'gst tax percentage', 'desc' => 'Calculate tax', 'icon' => 'GST'],
        ['name' => 'Percentage Calculator', 'slug' => 'percentage-calculator', 'category' => 'Finance', 'keywords' => 'percentage tax rate', 'desc' => 'Calculate percentage', 'icon' => '%'],
        ['name' => 'Password Generator', 'slug' => 'password-generator', 'category' => 'Security', 'keywords' => 'secure random password', 'desc' => 'Create passwords', 'icon' => 'KEY'],
    ];

    private array $articles = [
        ['title' => 'What is GST?', 'slug' => 'what-is-gst', 'category' => 'Finance', 'excerpt' => 'Understand GST tax rates and calculation.', 'meta_description' => 'GST tax guide', 'published_at' => '2026-07-01'],
        ['title' => 'Strong Password Guide', 'slug' => 'strong-password-guide', 'category' => 'Security', 'excerpt' => 'Create a secure password.', 'meta_description' => 'Password security', 'published_at' => '2026-07-02'],
    ];

    public function test_tool_suggestions_are_unique_and_exclude_current_tool(): void
    {
        $service = new InternalLinkingService($this->tools, $this->articles);
        $results = $service->relatedToolsForTool($this->tools[0], 8);
        $slugs = array_column($results, 'slug');

        $this->assertNotContains('gst-calculator', $slugs);
        $this->assertSame($slugs, array_values(array_unique($slugs)));
        $this->assertSame('percentage-calculator', $slugs[0]);
    }

    public function test_matching_connects_tools_and_articles_in_both_directions(): void
    {
        $service = new InternalLinkingService($this->tools, $this->articles);

        $this->assertSame('what-is-gst', $service->relatedArticlesForTool($this->tools[0], 1)[0]['slug']);
        $this->assertSame('gst-calculator', $service->relatedToolsForArticle($this->articles[0], 1)[0]['slug']);
    }

    public function test_article_suggestions_exclude_the_current_article(): void
    {
        $service = new InternalLinkingService($this->tools, $this->articles);
        $slugs = array_column($service->relatedArticlesForArticle($this->articles[0]), 'slug');

        $this->assertNotContains('what-is-gst', $slugs);
    }
}
