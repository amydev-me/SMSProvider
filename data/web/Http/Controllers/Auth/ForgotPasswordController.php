<?php

namespace Web\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Web\Http\Controllers\Controller;

use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use App\Models\User;

class ForgotPasswordController extends Controller
{
	use SendsPasswordResetEmails;

	//Shows form to request password reset
	public function showLinkRequestForm()
	{
		return view('email');
	}

	public function broker()
	{
		return Password::broker('users');
	}

	public function sendResetLinkEmail(Request $request)
	{
		$this->validateEmail($request);

		$user = User::where('email', $request->email)->first();

		if ($user) {
			if ($user->block == 1) {
				return redirect()->back()->withErrors(['errors' => 'Account Blocked! Contact support for details.']);
			}
		}

		$response = $this->broker()->sendResetLink(
			$request->only('email')
		);

		return $response == Password::RESET_LINK_SENT
					? $this->sendResetLinkResponse($request, $response)
					: $this->sendResetLinkFailedResponse($request, $response);
	}

	protected function validateEmail(Request $request)
	{
		$rules = [
			'email' => 'required|email',
			'g-recaptcha-response' => 'required|captcha'
		];

		$messages = [
			'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
			'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
		];

		$this->validate($request, $rules, $messages);
	}
}