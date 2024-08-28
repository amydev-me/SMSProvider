<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class DexatelAuthenticated
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
        $params = str_replace( '_', '', key($request->all()) );
        $params = json_decode($params, true);

        $username = 'operator';
        $mykey = 'Opt/LFRC';

        if ($mykey != $params['password']) {
            return response()->json(['status'=>'Authorization Failed '],401);
        }
        if ($username != $params['username']) {
            return response()->json(['status'=>'Authorization Failed '],401);
        }

        return $next($request);
    }
}
