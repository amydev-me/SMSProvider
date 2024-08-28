<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminLevelTwo
{
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::guard('admin')->user()->role != 2 && Auth::guard('admin')->user()->role != 3 && Auth::guard('admin')->user()->role != 4) {
			return response()->view('errors.404');
		}

		return $next($request);
	}
}