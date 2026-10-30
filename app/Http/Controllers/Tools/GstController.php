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
        $validated = $request->validate([
            'mode' => 'nullable|in:exclusive,inclusive',
            'amount' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:1',
        ]);

        $mode = $validated['mode'] ?? 'exclusive';
        $amount = (float) $validated['amount'];
        $rate = (float) $validated['rate'];

        if ($mode === 'inclusive') {
            $taxable = $amount / (1 + ($rate / 100));
            $gst = $amount - $taxable;
            $total = $amount;
        } else {
            $taxable = $amount;
            $gst = ($amount * $rate) / 100;
            $total = $amount + $gst;
        }

        return view('tools.gst', compact('mode', 'amount', 'rate', 'taxable', 'gst', 'total'));
    }
}
