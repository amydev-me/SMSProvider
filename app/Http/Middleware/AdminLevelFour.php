<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminLevelFour
{
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::guard('admin')->user()->role != 4) {
			return response()->view('errors.404');
		}

		return $next($request);
	}
}