<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmiController extends Controller
{
    public function index()
    {
        return view('tools.emi');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:1',
            'tenure' => 'required|numeric|min:1',
        ]);

        $P = $request->amount;
        $annualRate = $request->rate;
        $N = $request->tenure;

        $R = $annualRate / 12 / 100;

        $emi = ($P * $R * pow(1 + $R, $N)) / (pow(1 + $R, $N) - 1);
        $total = $emi * $N;
        $interest = $total - $P;

        return view('tools.emi', compact('P', 'annualRate', 'N', 'emi', 'total', 'interest'));
    }
}