<?php
namespace User\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\UserEmailChange;
use Illuminate\Support\Facades\Auth;

use App\Models\UserSetting;
use App\Models\SmsLog;
use App\Models\User;
use App\Models\Term;

use Validator;
use Hash;

class UserController extends Controller
{
	public function getUserProfile()
	{
		$user = User::where('id',Auth::guard('web')->user()->id)->select(['full_name as  fullName', 'mobile', 'email', 'address', 'company'])->first();

		return response()->json($user);
	}

	public function updateProfile(Request $request)
	{
		Auth::guard('web')->user()->update($request->all());
		return redirect()->back();
	}

	public function logDetailView(Request $request)
	{
		$sms_log = SmsLog::with('log_details')->where('user_id',Auth::guard('web')->user()->id)->where('id',$request->log_id)->first();
		return view('sms_logs.detail',compact('sms_log'));
	}

	/*public function checkOldPassword(Request $request)
	{
		$user = User::where('id', Auth::guard('web')->user()->id)->first();

		if ( Hash::check($request->old_password, $user->password) ) {
			return response()->json(['status' => true], 200);
		} else {
			return response()->json(['status' => false], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}*/

	public function changePassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'old_password' => 'required',
			'new_password' => 'required',
			'confirm_password' => 'required|same:new_password'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$user = User::where('id', Auth::guard('web')->user()->id)->first();

		if ($user) {
			if ( Hash::check($request->old_password, $user->password) ) {
				$user->update([ 'password' => Hash::make($request->new_password) ]);
				return response()->json(['status' => true], 200);
			} else {
				return response()->json(['status' => false, 'message' => ['old_password' => 'Old Password is Wrong']], 200);
			}
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function getUserSetting()
	{
		$user = UserSetting::where('user_id', Auth::guard('web')->user()->id)->first();

		return response()->json($user);
	}

	public function updateSetting(Request $request)
	{
		$rules = [
				'minimum_credit' => 'required|integer'
			];

		if (Auth::guard('web')->user()->sms_type == 'Package') {
			$messages = [
					'minimum_credit.required' => 'The minimum credit field is required.',
					'minimum_credit.integer' => 'The minimum credit must be an integer.'
				];
		} else {
			$messages = [
					'minimum_credit.required' => 'The minimum USD field is required.',
					'minimum_credit.integer' => 'The minimum USD must be an integer.'
				];
		}

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$setting = UserSetting::where('id', Auth::guard('web')->user()->id)->first();

		if ($setting) {
			$request['sent'] = 0;
			$setting->update($request->all());
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function changeEmail(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$user = User::where('id', Auth::guard('web')->user()->id)->first();

		if ($user) {
			if ($user->email == $request->email) {
				return response()->json(['status' => false, 'message' => ['email' => 'Please provide new email.']], 200);
			}

			$new_token = str_random(40);
			$user->update([ 'new_email' => $request->email, 'new_email_token' => $new_token ]);

			$user->notify(new UserEmailChange($user, $new_token));
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function updateEmail(Request $request)
	{
		if ($request->token) {
			$user = User::where('new_email_token', $request->token)->first();

			if ($user) {
				$email = $user->new_email;

				$user->update([
					'email' => $email,
					'new_email' => NULL,
					'new_email_token' => NULL
				]);

				return redirect()->intended('dashboard');
			}

			return 'Invalid Token';
		}
	}

	public function acceptTerms()
	{
		$user = User::where('id', Auth::guard('web')->user()->id)->first();

		if ($user) {
			$user->accept_updated_terms = 1;
			$user->save();
		}

		return redirect('dashboard');
	}
}