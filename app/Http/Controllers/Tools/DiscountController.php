<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        return view('tools.discount');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:1',
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        $price = $request->price;
        $discount = $request->discount;

        $discountAmount = ($price * $discount) / 100;
        $finalPrice = $price - $discountAmount;

        return view('tools.discount', compact('price', 'discount', 'discountAmount', 'finalPrice'));
    }
}