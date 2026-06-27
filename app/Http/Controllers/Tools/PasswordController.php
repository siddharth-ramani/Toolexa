<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function index()
    {
        return view('tools.password');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'length' => 'required|integer|min:6|max:64',
        ]);

        $length = (int) $request->length;
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return view('tools.password', compact('password', 'length'));
    }
}
