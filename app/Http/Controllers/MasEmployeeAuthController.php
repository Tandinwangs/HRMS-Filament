<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasEmployeeAuthController extends Controller
{
    public function login(Request $request)
    {
    $credentials = $request->only('email', 'password');

    if (Auth::guard('employee')->attempt($credentials)) {
        // Authentication passed...
        return redirect()->intended('dashboard');
    }
    }

}
