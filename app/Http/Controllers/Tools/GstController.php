<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GstController extends Controller
{
    public function index()
    {
        return view('tools.gst');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:1',
        ]);

        $amount = $request->amount;
        $rate = $request->rate;

        $gst = ($amount * $rate) / 100;
        $total = $amount + $gst;

        return view('tools.gst', compact('amount', 'rate', 'gst', 'total'));
    }
}