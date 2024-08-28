<?php

namespace App\Http\Middleware;

use App\Models\Term;

use Carbon\Carbon;

use Closure;
use Auth;

class UserAcceptTerms
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
		if (Auth::guard('web')->user()->accept_updated_terms == 0) {
			$term = Term::latest()->first();

			if ( $term->expire_at < \Carbon\Carbon::now() ) {
				return redirect('dashboard');
			}
		}

		return $next($request);
	}
}