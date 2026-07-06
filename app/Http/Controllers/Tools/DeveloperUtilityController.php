<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class DeveloperUtilityController extends Controller
{
    private array $slugs = [
        'json-formatter',
        'json-validator',
        'json-to-xml-converter',
        'xml-to-json-converter',
        'html-formatter',
        'css-minifier',
        'css-beautifier',
        'html-to-markdown-converter',
        'markdown-to-html-converter',
        'base64-encoder-decoder',
        'sql-formatter',
    ];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.developer-utility', ['slug' => $slug]);
    }
}
