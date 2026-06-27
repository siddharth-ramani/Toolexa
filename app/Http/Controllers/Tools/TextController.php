<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TextController extends Controller
{
    public function index()
    {
        return view('tools.text');
    }

    public function convert(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:5000',
        ]);

        $text = $request->text;

        return view('tools.text', [
            'text' => $text,
            'upper' => strtoupper($text),
            'lower' => strtolower($text),
            'title' => ucwords(strtolower($text)),
        ]);
    }
}
