<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function index()
    {
        return view('tools.qr');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
        ]);

        $text = $request->text;

        $qr = QrCode::size(200)->generate($text);

        return view('tools.qr', compact('text', 'qr'));
    }
}
