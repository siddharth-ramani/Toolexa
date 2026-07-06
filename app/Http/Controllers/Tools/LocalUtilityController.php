<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class LocalUtilityController extends Controller
{
    private array $slugs = [
        'hex-rgb-hsl-color-converter',
        'barcode-generator',
        'image-to-base64-converter',
        'vat-calculator',
        'robots-txt-generator',
        'password-strength-checker',
        'csv-to-json-converter',
        'timestamp-converter',
        'webp-to-png-converter',
        'keyword-density-checker',
        'ico-favicon-generator',
        'mortgage-calculator',
        'url-slug-generator',
        'color-picker-from-image',
    ];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.local-utility', ['slug' => $slug]);
    }
}
