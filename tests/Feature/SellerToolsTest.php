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
}
