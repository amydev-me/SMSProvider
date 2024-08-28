<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

use App\Models\User;

class UserObsolete
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::guard('web')->user()->block) {
			Auth::guard('web')->logout();
			return redirect('login');
		}

		return $next($request);
	}
}