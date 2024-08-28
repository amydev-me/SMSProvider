<?php

namespace User\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use User\Http\Controllers\Controller;
use App\Notifications\UserAccountVerify;

use Carbon\Carbon;
use App\Models\User;
use App\Models\VerifyUserEmail;

class ResendEmail extends Controller
{
	public function resendEmail()
	{
		$user = User::with('verify_user_email')->currentUser()->where('verified', false)->first();
		if ($user) {
			$verify_user = $user->verify_user_email;

			$_hour = 0;

			if($verify_user) {
				// if ($verify_user->resend_at->addHour($verify_user->resent_in) < Carbon::now()) {
					// if ($verify_user->resend_count > 0) {
					//	 $_hour = $verify_user->resend_count * 4;
					// }
					$token = str_random(40);

					$verify_user->where('user_id', $verify_user->user_id)->update([
						'user_id' => Auth::guard('web')->user()->id,
						'token' => $token,
						'expire_at' => Carbon::now()->addHour(24),
						'resend_at' => Carbon::now(),
						'resend_count' => $verify_user->resend_count + 1,
						'resent_in' => $_hour
					]);

					$user->notify(new UserAccountVerify($user, $token));

					$message = 'Please check your inbox. Token will expire in 24 hours.';
					return view('resend-email-failed', compact('message'));
				// } 
				// else {
				//	 $message = 'We already send. You can resend at next ' . $verify_user->resent_in . 'hours';
				//	 return view('resend-email-failed', compact('message'));
				// }
			}
		}

		$message = 'Already Activate Your Email Address';
		return view('resend-email-failed', compact('message'));
	}

	public function checkAlreadyVerifyEmail()
	{
		$isVerify = User::CurrentUser()->where('verified', true)->exists();
		return response()->json($isVerify ? false : true);
	}
}