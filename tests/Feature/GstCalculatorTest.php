<?php

namespace Tests\Feature;

use Tests\TestCase;

class GstCalculatorTest extends TestCase
{
    public function test_page_offers_exclusive_and_inclusive_modes(): void
    {
        $this->get('/tools/gst-calculator')
            ->assertOk()
            ->assertSee('GST exclusive — add GST')
            ->assertSee('GST inclusive — extract GST')
            ->assertSee('Taxable Value from Inclusive Total');
    }

    public function test_exclusive_mode_adds_gst_to_the_taxable_value(): void
    {
        $this->post('/tools/gst-calculator', [
            'mode' => 'exclusive',
            'amount' => 1000,
            'rate' => 18,
        ])->assertOk()
            ->assertSee('&#8377; 1,000.00', false)
            ->assertSee('&#8377; 180.00', false)
            ->assertSee('&#8377; 1,180.00', false);
    }

    public function test_inclusive_mode_extracts_gst_from_the_final_total(): void
    {
        $this->post('/tools/gst-calculator', [
            'mode' => 'inclusive',
            'amount' => 1180,
            'rate' => 18,
        ])->assertOk()
            ->assertSee('Taxable Value')
            ->assertSee('&#8377; 1,000.00', false)
            ->assertSee('&#8377; 180.00', false)
            ->assertSee('Total Amount: <strong>&#8377; 1,180.00</strong>', false);
    }

    public function test_existing_posts_without_a_mode_remain_exclusive(): void
    {
        $this->post('/tools/gst-calculator', [
            'amount' => 1000,
            'rate' => 18,
        ])->assertOk()
            ->assertSee('&#8377; 1,180.00', false);
    }
}
