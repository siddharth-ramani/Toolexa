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
        foreach ([
            'marketplace-profit-calculator', 'volumetric-weight-calculator', 'cod-fee-calculator',
            'marketplace-commission-calculator', 'break-even-selling-price-calculator',
            'return-rto-cost-calculator', 'shipping-cost-per-order-calculator',
            'inventory-reorder-point-calculator', 'ecommerce-roas-calculator',
            'discount-profit-calculator',
        ] as $slug) {
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

        $this->post('/tools/marketplace-commission-calculator', [
            'sale_price' => 1499, 'commission_rate' => 15, 'tax_rate' => 18,
        ])->assertOk()->assertSee('Total Commission Cost')->assertSee('Net After Commission');
        $this->post('/tools/break-even-selling-price-calculator', [
            'product_cost' => 500, 'fixed_fees' => 100, 'fee_rate' => 18, 'target_profit' => 150,
        ])->assertOk()->assertSee('Required Selling Price')->assertSee('914.63');
        $this->post('/tools/return-rto-cost-calculator', [
            'orders' => 100, 'return_rate' => 12, 'forward_cost' => 70, 'reverse_cost' => 85, 'handling_loss' => 40,
        ])->assertOk()->assertSee('Expected Return Loss')->assertSee('2,340.00');
        $this->post('/tools/shipping-cost-per-order-calculator', [
            'orders' => 250, 'freight_total' => 18000, 'packaging_total' => 5000, 'surcharge_total' => 2500,
        ])->assertOk()->assertSee('Average Shipping Cost per Order')->assertSee('102.00');
        $this->post('/tools/inventory-reorder-point-calculator', [
            'daily_sales' => 8, 'lead_days' => 12, 'safety_days' => 5,
        ])->assertOk()->assertSee('Reorder Point')->assertSee('136 units');
        $this->post('/tools/ecommerce-roas-calculator', [
            'revenue' => 75000, 'ad_spend' => 15000, 'gross_margin' => 35,
        ])->assertOk()->assertSee('ROAS')->assertSee('5.00x');
        $this->post('/tools/discount-profit-calculator', [
            'list_price' => 1299, 'discount_rate' => 10, 'product_cost' => 500, 'fee_rate' => 17, 'fixed_cost' => 95,
        ])->assertOk()->assertSee('Profit after Discount')->assertSee('Effective Margin');
    }

    public function test_new_seller_guides_are_published_and_link_back_to_tools(): void
    {
        foreach ([
            'how-to-calculate-marketplace-profit-per-order' => 'Marketplace Profit Calculator',
            'volumetric-weight-ecommerce-shipping-guide' => 'Volumetric Weight Calculator',
            'cod-charges-seller-payout-explained' => 'COD Fee Calculator',
            'marketplace-commission-seller-fees-guide' => 'Marketplace Commission Calculator',
            'break-even-selling-price-for-online-sellers' => 'Break-even Selling Price Calculator',
            'return-rto-costs-ecommerce-sellers' => 'Return & RTO Cost Calculator',
            'shipping-cost-per-order-seller-guide' => 'Shipping Cost per Order Calculator',
            'inventory-reorder-point-online-sellers' => 'Inventory Reorder Point Calculator',
            'ecommerce-roas-advertising-guide' => 'Ecommerce ROAS Calculator',
            'discounts-and-ecommerce-profit-guide' => 'Discount Profit Calculator',
        ] as $slug => $toolName) {
            $this->get('/blog/'.$slug)
                ->assertOk()
                ->assertSee($toolName)
                ->assertSee('FAQPage');
        }
    }
}
