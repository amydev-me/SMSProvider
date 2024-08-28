<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SmsHelper;

use App\Models\Admin;

use Closure;
use Auth;

class AdminSmsAuthenticate
{
	use SmsHelper;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$secret = $request->bearerToken();

		$admin = Admin::whereHas('admin_tokens', function ($q) use ($secret) {
							$q->where('api_secret', $secret);
						})
						->first();

		if ( !$admin ) {
			return response()->json($this->getResponseMessage(401, 'Unauthorized Token is invalid.'), 401);
		}

		Auth::guard('admin')->login($admin);
		$access_token = Auth::guard('admin')->user()->createToken($request->key)->accessToken;

		if ( !$access_token ) {
			return response()->json($this->getResponseMessage(401, 'Unauthorized Token is invalid.'), 401);
		}

		$request['token'] = $access_token;

		return $next($request);
	}
}
