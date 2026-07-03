<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class SellerToolController extends Controller
{
    private array $slugs = [
        'meesho-label-cropper',
        'amazon-label-cropper',
        'flipkart-label-cropper',
        'myntra-label-cropper',
        'ajio-label-cropper',
    ];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.seller-label', ['slug' => $slug]);
    }
}
