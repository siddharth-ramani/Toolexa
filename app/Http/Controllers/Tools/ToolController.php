<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class ToolController extends Controller
{
    private array $controllers = [
        'gst-calculator' => GstController::class,
        'emi-calculator' => EmiController::class,
        'age-calculator' => AgeController::class,
        'percentage-calculator' => PercentageController::class,
        'discount-calculator' => DiscountController::class,
        'simple-interest-calculator' => InterestController::class,
        'password-generator' => PasswordController::class,
        'unit-converter' => UnitController::class,
        'qr-generator' => QrController::class,
        'text-case-converter' => TextController::class,
        'word-counter' => TextUtilityController::class,
        'character-counter' => TextUtilityController::class,
        'remove-duplicate-lines' => TextUtilityController::class,
        'remove-extra-spaces' => TextUtilityController::class,
        'text-repeater' => TextUtilityController::class,
        'base64-encoder' => TextUtilityController::class,
        'base64-decoder' => TextUtilityController::class,
        'url-encoder-decoder' => TextUtilityController::class,
        'md5-hash-generator' => TextUtilityController::class,
        'lorem-ipsum-generator' => TextUtilityController::class,
        'image-resizer' => ImageToolController::class,
        'image-compressor' => ImageToolController::class,
        'jpg-to-png-converter' => ImageToolController::class,
        'png-to-jpg-converter' => ImageToolController::class,
        'image-cropper' => ImageToolController::class,
        'hex-rgb-hsl-color-converter' => LocalUtilityController::class,
        'barcode-generator' => LocalUtilityController::class,
        'image-to-base64-converter' => LocalUtilityController::class,
        'vat-calculator' => LocalUtilityController::class,
        'robots-txt-generator' => LocalUtilityController::class,
        'password-strength-checker' => LocalUtilityController::class,
        'csv-to-json-converter' => LocalUtilityController::class,
        'timestamp-converter' => LocalUtilityController::class,
        'webp-to-png-converter' => LocalUtilityController::class,
        'keyword-density-checker' => LocalUtilityController::class,
        'ico-favicon-generator' => LocalUtilityController::class,
        'mortgage-calculator' => LocalUtilityController::class,
        'url-slug-generator' => LocalUtilityController::class,
        'color-picker-from-image' => LocalUtilityController::class,
        'uuid-generator' => BrowserUtilityController::class,
        'random-number-generator' => BrowserUtilityController::class,
        'random-string-generator' => BrowserUtilityController::class,
        'uuid-validator' => BrowserUtilityController::class,
        'binary-decimal-converter' => BrowserUtilityController::class,
        'json-formatter' => DeveloperUtilityController::class,
        'json-validator' => DeveloperUtilityController::class,
        'json-to-xml-converter' => DeveloperUtilityController::class,
        'xml-to-json-converter' => DeveloperUtilityController::class,
        'html-formatter' => DeveloperUtilityController::class,
        'css-minifier' => DeveloperUtilityController::class,
        'css-beautifier' => DeveloperUtilityController::class,
        'html-to-markdown-converter' => DeveloperUtilityController::class,
        'markdown-to-html-converter' => DeveloperUtilityController::class,
        'base64-encoder-decoder' => DeveloperUtilityController::class,
        'sql-formatter' => DeveloperUtilityController::class,
        'image-to-pdf-converter' => PdfToolController::class,
        'pdf-page-counter' => PdfToolController::class,
        'pdf-metadata-viewer' => PdfToolController::class,
        'pdf-password-checker' => PdfToolController::class,
        'pdf-merger' => PdfToolController::class,
        'meesho-label-cropper' => SellerToolController::class,
        'amazon-label-cropper' => SellerToolController::class,
        'flipkart-label-cropper' => SellerToolController::class,
        'myntra-label-cropper' => SellerToolController::class,
        'ajio-label-cropper' => SellerToolController::class,
    ];

    public function show(string $slug)
    {
        $tool = HomeController::toolBySlug($slug);

        if ($tool && ($tool['view'] ?? null) === 'finance-calculator') {
            return app(FinanceCalculatorController::class)->index($slug);
        }

        abort_unless(isset($this->controllers[$slug]), 404);

        if ($this->controllers[$slug] === TextUtilityController::class) {
            return app(TextUtilityController::class)->index($slug);
        }

        if ($this->controllers[$slug] === ImageToolController::class) {
            return app(ImageToolController::class)->index($slug);
        }

        if ($this->controllers[$slug] === BrowserUtilityController::class) {
            return app(BrowserUtilityController::class)->index($slug);
        }

        if ($this->controllers[$slug] === DeveloperUtilityController::class) {
            return app(DeveloperUtilityController::class)->index($slug);
        }

        if ($this->controllers[$slug] === LocalUtilityController::class) {
            return app(LocalUtilityController::class)->index($slug);
        }

        if ($this->controllers[$slug] === PdfToolController::class) {
            return app(PdfToolController::class)->index($slug);
        }

        if ($this->controllers[$slug] === SellerToolController::class) {
            return app(SellerToolController::class)->index($slug);
        }

        return app($this->controllers[$slug])->index();
    }
}
