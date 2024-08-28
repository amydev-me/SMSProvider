<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Term;
use App\Models\User;

use Validator;
use Mail;
use DB;

class TermsController extends Controller
{
	public function index()
	{
		$term = Term::latest()->first();
		return view('admin-views.terms.list', compact('term'));
	}

	public function edit()
	{
		$term = Term::latest()->first();
		return view('admin-views.terms.edit', compact('term'));
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'expire_at' => 'nullable|date_format:Y-m-d',
			'text' => 'required',
			'email' => 'nullable',
			'subject' => 'required_if:email,checked',
			'body' => 'required_if:email,checked'
		]);

		if ( $validator->fails() ) {
			return redirect('admin/terms/edit')
				->withErrors($validator,'post')
				->withInput();
		}

		Term::create($request->all());

		if ($request->expire_at != NULL) {
			DB::table('users')->update(['accept_updated_terms' => 0]);
		}

		if ($request->email == 'checked') {
			$this->sendUpdatedTermsEmail($request->all());
		}

		return redirect('admin/terms');
	}

	private function sendUpdatedTermsEmail($request)
	{
		$emails = User::where('obsolete', '0')
					->where('block', '0')
					->pluck('email')
					->toArray();

		$template = 'admin-mail.terms-update';

		$subject = $request['subject'];

		if ($emails) {
			foreach ($emails as $key) {
				Mail::send($template, compact('request'), function($message) use ($key, $subject) {
					$message->to($key)
							->subject($subject);
				});
			}
		}
	}
}