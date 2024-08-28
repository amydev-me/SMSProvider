<?php

namespace Web\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class VerifyEmail extends Controller
{
    public function verify($token)
    {
        if ($token) {
            $user = User::whereHas('verify_user_email', function ($q) use ($token) {
                $q->where('token', $token);
            })->first();
            if ($user) {
                $user->update(['verified' => true]);
                $user->verify_user_email()->delete();
                return redirect()->intended('dashboard');
            }
            return 'invalid token';
        }
    }
}
