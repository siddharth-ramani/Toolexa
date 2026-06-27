<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PercentageController extends Controller
{
    public function index()
    {
        return view('tools.percentage');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:1',
            'value' => 'required|numeric|min:0',
        ]);

        $total = $request->total;
        $value = $request->value;

        $percentage = ($value / $total) * 100;

        return view('tools.percentage', compact('total', 'value', 'percentage'));
    }
}