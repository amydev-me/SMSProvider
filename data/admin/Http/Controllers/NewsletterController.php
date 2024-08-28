<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Mail;

class NewsletterController extends Controller
{
	public function index()
	{
		return view('admin-views.newsletter');
	}

	public function send(Request $request)
	{
		$subject = $request->subject;
		$text = $request->text;

		$template = 'admin-mail.newsletter';
		$emails = User::where('block', '0')->where('newsletter', '1')->pluck('email')->toArray();

		if (count($emails) > 0) {
			Mail::send($template, compact('text'), function($message) use ($emails, $subject) {
				$message->bcc($emails)
						->subject($subject);
			});

			return response()->json(['status' => true], 200);
		} else {
			return response()->json(['status' => false, 'message' => "No Email to Send"], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}
}