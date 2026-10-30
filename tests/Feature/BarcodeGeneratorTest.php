<?php

namespace Tests\Feature;

use Tests\TestCase;

class BarcodeGeneratorTest extends TestCase
{
    public function test_page_explains_formats_and_validation_without_claiming_registration(): void
    {
        $this->get('/tools/barcode-generator')
            ->assertOk()
            ->assertSee('Code 128 supports printable ASCII')
            ->assertSee('EAN-13 requires 12 or 13 digits')
            ->assertSee('It does not create GS1-128', false)
            ->assertSee('does not issue, register or verify ownership', false)
            ->assertSee('4006381333931')
            ->assertSee('036000291452')
            ->assertSee('GS1 General Specifications');
    }

    public function test_runtime_asset_contains_real_code_128_and_retail_check_digit_validation(): void
    {
        $javascript = file_get_contents(public_path('assets/js/app.min.js'));

        $this->assertIsString($javascript);
        $this->assertStringContainsString("codes = [104].concat(codes, [checksum % 103, 106])", $javascript);
        $this->assertStringContainsString("'2331112'", $javascript);
        $this->assertStringContainsString('UPC-A requires exactly 11 data digits or a complete 12-digit code.', $javascript);
        $this->assertStringContainsString('The EAN-13 check digit is invalid.', $javascript);
        $this->assertStringNotContainsString("if (type === 'code39' || type === 'code128')", $javascript);
    }

    public function test_barcode_input_is_accessible_and_bounded(): void
    {
        $this->get('/tools/barcode-generator')
            ->assertOk()
            ->assertSee('aria-describedby="barcodeHelp"', false)
            ->assertSee('maxlength="48"', false)
            ->assertSee('Download SVG')
            ->assertSee('Download PNG');
    }
}
