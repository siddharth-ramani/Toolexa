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

    public function test_gst_inclusive_article_uses_reconciled_examples_and_clear_scope(): void
    {
        $response = $this->get('/blog/gst-inclusive-vs-gst-exclusive-explained')->assertOk();
        $html = $response->getContent();

        $response
            ->assertSee('<title>GST Inclusive vs Exclusive: Formula &amp; ₹1,180 Example</title>', false)
            ->assertSee('₹1,000 × 18 ÷ 100 = ₹180')
            ->assertSee('₹1,180 ÷ 1.18 = ₹1,000')
            ->assertSee('Why 18% of an Inclusive Total Is Wrong')
            ->assertSee('What the Calculator Does Not Decide')
            ->assertSee('Central Board of Indirect Taxes and Customs')
            ->assertDontSee('Use the examples as a method, not merely as answers to copy')
            ->assertDontSee('Who should read this GST Inclusive');

        $this->assertSame(1, substr_count($html, '<h2>Why 18% of an Inclusive Total Is Wrong</h2>'));
    }

    public function test_uuid_article_uses_current_standard_and_security_boundaries(): void
    {
        $response = $this->get('/blog/uuid-explained-what-it-is-and-why-developers-use-it')->assertOk();
        $html = $response->getContent();

        $response
            ->assertSee('<title>What Is a UUID? Format, Versions &amp; UUID v4 Example</title>', false)
            ->assertSee('RFC 9562, the current IETF UUID standard')
            ->assertSee('122 random bits')
            ->assertSee('The 128-bit and 8-4-4-4-12 Structure')
            ->assertSee('A UUID Is Not a Security Boundary')
            ->assertSee('RFC 9562 — Universally Unique IDentifiers')
            ->assertSee('MDN — Crypto.randomUUID()')
            ->assertDontSee('Use the examples as a method, not merely as answers to copy')
            ->assertDontSee('Who should read this What Is a UUID');

        $this->assertSame(1, substr_count($html, '<h2>A UUID Is Not a Security Boundary</h2>'));
    }
}
