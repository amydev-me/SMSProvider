<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class NhnAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $username = 'operator';
        $mykey = 'Opt/LFRC';

        if ($mykey != $request->password) {
            return response()->json(['status'=>'Authorization Failed '],401);
        }
        if ($username != $request->username) {
            return response()->json(['status'=>'Authorization Failed '],401);
        }

        return $next($request);
    }
}
