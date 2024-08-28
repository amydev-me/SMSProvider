<?php
namespace Admin\Http\Controllers\Auth;

use Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin;
use Carbon\Carbon;
use Validator;

class AdminLoginController extends Controller
{
	public function showLoginForm()
	{
		if (Auth::guard('admin')->check()) {
			return redirect('/admin/order');
		}

		return view('admin-views.login');
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
			if (Auth::guard('admin')->check()) {
				Admin::where('id', Auth::guard('admin')->user()->id)->update(['last_login' => Carbon::now()]);
				return redirect()->intended(route('admin.dashboard.index'));
			}

			return redirect()->back()->withErrors(['errors' => 'This account is deleted!'])->withInput($request->except('password'));
		}

		return redirect()->back()->withErrors(['errors' => 'Username or Password wrong!'])->withInput($request->except('password'));
	}

	public static function attemptLogin(Request $request)
	{
		$admin = Admin::where('username', $request->username)->where('obsolete', '0')->first();

		if ($admin) {
			if ($admin->obsolete == 1) {
				return 'obsolete';
			}

			if ( Hash::check($request->password, $admin->password) ) {
				return Auth::guard('admin')->attempt([
					'username' => $request->username,
					'password' => $request->password,
					'obsolete' => '0'
				], $request->remember);
			}
		}

		return FALSE;
	}
	
	public function logout()
	{
		Auth::guard('admin')->logout();
		return redirect('/admin');
	}
}