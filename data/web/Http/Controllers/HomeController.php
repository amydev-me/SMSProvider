<?php

namespace Web\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DefaultSetting;
use App\Models\Term;

use Validator;
use Mail;

class HomeController extends Controller
{
	public function showHomePage()
	{
		$setting = DefaultSetting::first();
		return view('home', compact('setting'));
	}

	public function showContactPage()
	{
		$setting = DefaultSetting::first();
		return view('contact-us', compact('setting'));
	}

	public function contact(Request $request)
	{
		$rules = [
			'name' => 'required',
			'email' => 'required|email',
			'text' => 'required',
			'g-recaptcha-response' => 'required|captcha'
		];

		$messages = [
			'text.required' => 'The message field is required.',
			'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
			'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
		];

		$this->validate($request, $rules, $messages);

		$subject = 'There is an incoming contact message from ' . $request->email;
		$template = 'contact-mail';

		Mail::send($template, ['text' => $request->text], function($message) use ($request, $subject) {
			$message->from($request->email, $request->name)
					->subject($subject)
					->to('info@triplesms.com');
		});

		return redirect('/');
	}

	public function showTerms()
	{
		$term = Term::latest()->first();
		return view('terms', compact('term'));
	}
}