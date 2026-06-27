<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function index()
    {
        return view('tools.interest');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'principal' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:1',
            'time' => 'required|numeric|min:1',
        ]);

        $P = $request->principal;
        $R = $request->rate;
        $T = $request->time;

        $interest = ($P * $R * $T) / 100;
        $total = $P + $interest;

        return view('tools.interest', compact('P', 'R', 'T', 'interest', 'total'));
    }
}