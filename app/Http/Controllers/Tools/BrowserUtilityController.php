<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class BrowserUtilityController extends Controller
{
    private array $slugs = [
        'uuid-generator',
        'random-number-generator',
        'random-string-generator',
        'uuid-validator',
        'binary-decimal-converter',
    ];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.browser-utility', ['slug' => $slug]);
    }
}
