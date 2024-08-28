<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SmsHelper;

use App\Models\User;
use App\Models\Term;

use Carbon\Carbon;

use Closure;
use Auth;

class ApiAuthenticate
{
	use SmsHelper;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$secret = $request->bearerToken();

		$user = User::whereHas('user_tokens', function ($q) use ($secret) {
						$q->where('api_secret', $secret);
					})
					->first();

		if ( !$user ) {
			return response()->json($this->getResponseMessage(401, 'Unauthorized Token is invalid.'), 401);
		}

		if ( $user->block == '1' ) {
			return response()->json($this->getResponseMessage(401, 'Account is blocked.'), 401);
		}

		Auth::guard('web')->login($user);
		$access_token = Auth::guard('web')->user()->createToken($request->key)->accessToken;

		if ( !$access_token ) {
			return response()->json($this->getResponseMessage(401, 'Unauthorized Token is invalid.'), 401);
		}

		$isVerify = User::CurrentUser()->where('verified', TRUE)->exists();

		if ( !$isVerify ) {
			return response()->json($this->getResponseMessage(04), 400);
		}

		if ($user->accept_updated_terms == 0) {
			$term = Term::latest()->first();

			if ( $term->expire_at < Carbon::now() ) {
				return response()->json($this->getResponseMessage(403, 'Your services are limited until you accept our updated Terms & Conditions. Login to web portal and accept terms.'), 403);
			}
		}

		$request['token'] = $access_token;

		return $next($request);
	}
}