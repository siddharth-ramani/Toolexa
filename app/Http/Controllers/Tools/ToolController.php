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
        'uuid-generator' => BrowserUtilityController::class,
        'random-number-generator' => BrowserUtilityController::class,
        'random-string-generator' => BrowserUtilityController::class,
        'uuid-validator' => BrowserUtilityController::class,
        'binary-decimal-converter' => BrowserUtilityController::class,
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

        if ($this->controllers[$slug] === PdfToolController::class) {
            return app(PdfToolController::class)->index($slug);
        }

        if ($this->controllers[$slug] === SellerToolController::class) {
            return app(SellerToolController::class)->index($slug);
        }

        return app($this->controllers[$slug])->index();
    }
}
