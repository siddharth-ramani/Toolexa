<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class AdvancedBrowserToolController extends Controller
{
    private array $slugs = ['sha-256-hash-generator', 'url-parser', 'color-palette-generator', 'pdf-splitter', 'text-compare-tool', 'open-graph-meta-tag-generator', 'html-entity-encoder-decoder', 'time-zone-converter', 'uuid-batch-generator', 'xml-sitemap-generator', 'json-minifier', 'css-gradient-generator', 'png-to-svg-converter', 'text-sorter', 'image-rotator-flipper', 'regex-tester', 'uuid-decoder-inspector', 'reading-time-calculator', 'screen-resolution-checker'];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.advanced-browser-tool', compact('slug'));
    }
}
