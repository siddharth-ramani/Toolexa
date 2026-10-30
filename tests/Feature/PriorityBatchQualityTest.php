<?php

namespace Tests\Feature;

use Tests\TestCase;

class PriorityBatchQualityTest extends TestCase
{
    public function test_inflation_calculator_uses_verified_compounding_and_honest_boundaries(): void
    {
        $this->get('/tools/inflation-calculator')
            ->assertOk()
            ->assertSee('It does not retrieve India’s current CPI', false)
            ->assertSee('₹8,95,423.85', false)
            ->assertSee('Consumer Price Index catalogue');

        $this->post('/tools/inflation-calculator', [
            'current_cost' => 500000,
            'rate' => 6,
            'years' => 10,
        ])->assertOk()
            ->assertSee('895,423.85')
            ->assertSee('395,423.85');
    }

    public function test_gold_loan_page_matches_emi_functionality_and_verified_example(): void
    {
        $this->get('/tools/gold-loan-calculator')
            ->assertOk()
            ->assertSee('models EMI only', false)
            ->assertSee('₹8,884.88', false)
            ->assertSee('Does not calculate LTV')
            ->assertSee('RBI notice on gold-loan practices');

        $this->post('/tools/gold-loan-calculator', [
            'amount' => 100000,
            'rate' => 12,
            'tenure_years' => 1,
        ])->assertOk()
            ->assertSee('8,884.88')
            ->assertSee('6,618.55');
    }

    public function test_priority_articles_use_specific_original_guidance(): void
    {
        $this->get('/blog/how-inflation-affects-your-savings')
            ->assertOk()
            ->assertSee('about 0.94%', false)
            ->assertSee('MoSPI Consumer Price Index catalogue');

        $this->get('/blog/how-to-calculate-inflation-rate-easily')
            ->assertOk()
            ->assertSee('125.0 to 132.5')
            ->assertSee('Use Percentage Calculator for two historical values');

        $this->get('/blog/how-to-choose-the-right-color-palette')
            ->assertOk()
            ->assertSee('4.499:1 does not meet a 4.5:1 threshold')
            ->assertSee('W3C Understanding SC 1.4.3');

        $this->get('/blog/gold-loan-explained-benefits-risks-emi-calculation')
            ->assertOk()
            ->assertSee('does not appraise gold', false)
            ->assertSee('₹8,884.88', false)
            ->assertSee('RBI notice on gold-loan practices');
    }

    public function test_priority_comparisons_are_specific_and_source_backed(): void
    {
        $this->get('/compare/fd-vs-sip')
            ->assertOk()
            ->assertSee('<title>FD vs SIP: Risk, Returns, Liquidity &amp; Goal Comparison</title>', false)
            ->assertSee('DICGC currently insures eligible deposits up to ₹5 lakh', false)
            ->assertSee('SEBI SIP Calculator and disclaimer');

        $this->get('/compare/webp-vs-png')
            ->assertOk()
            ->assertSee('<title>WebP vs PNG: File Size, Quality &amp; Transparency Compared</title>', false)
            ->assertSee('Does Toolexa’s WebP to PNG converter preserve animation?', false)
            ->assertSee('WebP container specification');
    }
}
