<?php

namespace User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authentication extends Controller
{
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

//        $request->session()->invalidate();

        return redirect('login');
    }
}