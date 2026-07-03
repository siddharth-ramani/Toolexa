<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class ImageToolController extends Controller
{
    private array $slugs = [
        'image-resizer',
        'image-compressor',
        'jpg-to-png-converter',
        'png-to-jpg-converter',
        'image-cropper',
    ];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.image-utility', ['slug' => $slug]);
    }
}
