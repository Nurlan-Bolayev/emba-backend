<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $attrs = $request->validate([
            'email' => 'required',
            'password' => 'required|min:5',
        ]);

        if (Auth::guard('admin')->attempt($attrs)) {
            return Auth::guard('admin')->user();
        }

        throw ValidationException::withMessages([
            'email' => ['Invalid credentials.'],
        ]);
    }
}
