<?php
namespace Admin\Http\Controllers\Auth;

use Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin;
use Carbon\Carbon;
use Validator;

class LogsLoginController extends Controller
{
	public function showLoginForm()
	{
		if (Auth::guard('log')->check()) {
			return redirect('/dashboard-user');
		}

		return view('admin-view-logs.login');
	}
	
	public function login(Request $request)
	{
		// Validate the form data
		$validator = Validator::make($request->all(), [
			'username' => 'required|string',
			'password' => 'required'
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
		}

		if (self::attemptLogin($request)) {
			Admin::where('id', Auth::guard('log')->user()->id)->update(['last_login' => Carbon::now()]);
			return redirect()->intended(route('dashboard-user.index'));
		}

		return redirect()->back()->withErrors(['errors' => 'Username or Password wrong!'])->withInput($request->except('password'));
	}

	public static function attemptLogin(Request $request)
	{
		if ($request->username === 'logadmin') {
			$admin = Admin::where('username', $request->username)->first();

			if ($admin) {
				if ( Hash::check($request->password, $admin->password) ) {
					return Auth::guard('log')->attempt(['username' => $request->username, 'password' => $request->password], $request->remember);
				}
			}
		}
		return FALSE;
	}
	
	public function logout()
	{
		Auth::guard('log')->logout();
		return redirect('/dashboard-user');
	}
}