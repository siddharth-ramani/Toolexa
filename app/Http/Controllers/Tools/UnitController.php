<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return view('tools.unit');
    }

    public function convert(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric|min:0',
            'type' => 'required|in:km_to_m,m_to_km,kg_to_g,g_to_kg',
        ]);

        $value = (float) $request->value;
        $type = $request->type;

        $result = match ($type) {
            'km_to_m', 'kg_to_g' => $value * 1000,
            'm_to_km', 'g_to_kg' => $value / 1000,
        };

        return view('tools.unit', compact('value', 'type', 'result'));
    }
}
