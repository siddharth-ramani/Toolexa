<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class PdfToolController extends Controller
{
    private array $slugs = [
        'image-to-pdf-converter',
        'pdf-page-counter',
        'pdf-metadata-viewer',
        'pdf-password-checker',
        'pdf-merger',
    ];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.pdf-utility', ['slug' => $slug]);
    }
}
