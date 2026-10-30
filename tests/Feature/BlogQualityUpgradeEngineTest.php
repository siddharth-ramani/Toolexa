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
            $manuallyEdited = (bool) config('blog_quality.articles.'.$article['slug'].'.manually_edited', false);

            if ($manuallyEdited) {
                $this->assertGreaterThanOrEqual(3, count($quality['summary']), $article['slug']);
                $this->assertGreaterThanOrEqual(100, str_word_count(implode(' ', $quality['introduction'])), $article['slug']);
                $this->assertGreaterThanOrEqual(6, count($quality['faqs']), $article['slug']);
            } else {
                $this->assertCount(5, $quality['summary'], $article['slug']);
                $this->assertGreaterThanOrEqual(300, str_word_count(implode(' ', $quality['introduction'])), $article['slug']);
                $this->assertGreaterThanOrEqual(8, count($quality['faqs']), $article['slug']);
            }
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

    public function test_marketplace_profit_article_has_one_reconciled_example_without_generic_padding(): void
    {
        $response = $this->get('/blog/how-to-calculate-marketplace-profit-per-order')->assertOk();
        $html = $response->getContent();

        $response
            ->assertSee('One Reconciled Worked Example')
            ->assertSee('estimated payout is ₹999 − ₹300.72 = ₹698.28')
            ->assertSee('The Fee-tax Assumption You Must Check')
            ->assertSee('Limitations of the Calculator')
            ->assertSee('Content Version</dt><dd>2.0', false)
            ->assertDontSee('Use the examples as a method, not merely as answers to copy')
            ->assertDontSee('Who should read this How to Calculate Marketplace Profit per Order guide?');

        $this->assertSame(1, substr_count($html, '<h2>One Reconciled Worked Example</h2>'));
    }

    public function test_cod_article_matches_the_calculator_and_removes_generic_padding(): void
    {
        $response = $this->get('/blog/cod-charges-seller-payout-explained')->assertOk();
        $html = $response->getContent();

        $response
            ->assertSee('<title>COD Charges for Sellers: Fee, Tax &amp; Payout Example</title>', false)
            ->assertSee('One Reconciled ₹1,200 Example')
            ->assertSee('Total COD cost is ₹54 + ₹9.72 = ₹63.72')
            ->assertSee('estimated payout of ₹1,200 − ₹63.72 = ₹1,136.28')
            ->assertSee('Payout Is Not Seller Profit')
            ->assertSee('Limitations of This Estimate')
            ->assertSee('Content Version</dt><dd>2.0', false)
            ->assertDontSee('Use the examples as a method, not merely as answers to copy')
            ->assertDontSee('Who should read this COD Charges');

        $this->assertSame(1, substr_count($html, '<h2>One Reconciled ₹1,200 Example</h2>'));
    }
}
