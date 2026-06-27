<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgeController extends Controller
{
    public function index()
    {
        return view('tools.age');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'dob' => 'required|date|before_or_equal:today',
        ]);

        $dob = Carbon::parse($request->dob);
        $now = Carbon::now();

        $years = $dob->diffInYears($now);
        $months = $dob->copy()->addYears($years)->diffInMonths($now);
        $days = $dob->copy()->addYears($years)->addMonths($months)->diffInDays($now);

        return view('tools.age', compact('dob', 'years', 'months', 'days'));
    }
}
