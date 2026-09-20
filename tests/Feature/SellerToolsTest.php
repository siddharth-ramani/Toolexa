<?php

namespace Tests\Feature;

use Tests\TestCase;

class SellerToolsTest extends TestCase
{
    public function test_every_seller_cropper_uses_the_shared_print_ready_engine(): void
    {
        foreach ([
            'meesho-label-cropper',
            'amazon-label-cropper',
            'flipkart-label-cropper',
            'myntra-label-cropper',
            'ajio-label-cropper',
        ] as $slug) {
            $this->get('/tools/'.$slug)
                ->assertOk()
                ->assertSee('data-seller-crop-tool', false)
                ->assertSee('4 × 6 Thermal')
                ->assertSee('A4 Sheet')
                ->assertSee('Exact Crop Size')
                ->assertSee('Process &amp; Download Labels', false)
                ->assertSee('/assets/js/vendor/pdf-lib.min.js', false)
                ->assertDontSee('unpkg.com/pdf-lib', false);
        }
    }

    public function test_crop_engine_contains_bulk_layout_safety_and_local_dependencies(): void
    {
        $engine = file_get_contents(public_path('assets/js/seller-crop-engine.js'));

        $this->assertStringContainsString("outputLayout === 'thermal'", $engine);
        $this->assertStringContainsString("outputLayout === 'a4_grid'", $engine);
        $this->assertStringContainsString("'/vendor/pdf.min.js?v='", $engine);
        $this->assertStringContainsString('engine.clear();', $engine);
        $this->assertStringContainsString('exportBtn.disabled = true;', $engine);

        foreach (['pdf-lib.min.js', 'pdf.min.js', 'pdf.worker.min.js'] as $asset) {
            $this->assertFileExists(public_path('assets/js/vendor/'.$asset));
            $this->assertGreaterThan(10_000, filesize(public_path('assets/js/vendor/'.$asset)));
        }
    }

    public function test_seller_calculators_render_and_calculate_real_results(): void
    {
        foreach (['marketplace-profit-calculator', 'volumetric-weight-calculator', 'cod-fee-calculator'] as $slug) {
            $this->get('/tools/'.$slug)->assertOk()->assertSee('Seller Tools tool');
        }

        $this->post('/tools/marketplace-profit-calculator', [
            'sale_price' => 1000, 'product_cost' => 400, 'commission_rate' => 10,
            'shipping_fee' => 50, 'other_fees' => 20, 'fee_tax_rate' => 18,
        ])->assertOk()->assertSee('Estimated Profit')->assertSee('Profit Margin');

        $this->post('/tools/volumetric-weight-calculator', [
            'length' => 30, 'width' => 20, 'height' => 15, 'actual_weight' => 1.2, 'divisor' => 5000,
        ])->assertOk()->assertSee('1.80 kg')->assertSee('Chargeable Weight');

        $this->post('/tools/cod-fee-calculator', [
            'order_value' => 1200, 'cod_rate' => 2, 'fixed_fee' => 30, 'tax_rate' => 18,
        ])->assertOk()->assertSee('Total COD Cost')->assertSee('Estimated Payout');
    }

    public function test_new_seller_guides_are_published_and_link_back_to_tools(): void
    {
        foreach ([
            'how-to-calculate-marketplace-profit-per-order' => 'Marketplace Profit Calculator',
            'volumetric-weight-ecommerce-shipping-guide' => 'Volumetric Weight Calculator',
            'cod-charges-seller-payout-explained' => 'COD Fee Calculator',
        ] as $slug => $toolName) {
            $this->get('/blog/'.$slug)
                ->assertOk()
                ->assertSee($toolName)
                ->assertSee('FAQPage');
        }
    }
}
