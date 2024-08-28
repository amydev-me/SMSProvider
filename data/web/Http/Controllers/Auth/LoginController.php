<?php
/**
 * Created by PhpStorm.
 * User: Angelo
 * Date: 01/07/2018
 * Time: 4:59 PM
 */
namespace Web\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Web\Http\Controllers\Controller;

class LoginController extends Controller
{
//	public function __construct()
//	{
//		$this->middleware('guest')->except('logout');
//	}

	public function login(Request $request)
	{
		$res = $this->attemptLogin($request);

		if ($res) {
			if ($res == 'block') {
				return redirect()->back()->withErrors(['errors' => 'Account Blocked! Contact support for details.']);
			}

			$request->session()->regenerate();

			if (Auth::guard('web')->check()) {
				return redirect()->intended('dashboard');
			}
		}

		return redirect()->back()->withErrors(['errors' => 'Username or Password Wrong!'])->withInput($request->except('password'));
	}

	public function attemptLogin(Request $request)
	{
		$creditendial = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? $this->email() : $this->username();
		$user = User::where($creditendial, $request->username)->first();

		if ($user) {
			if ($user->block == 1) {
				return 'block';
			}

			if (Hash::check($request->password, $user->password)) {
				return Auth::guard('web')->attempt([
					$creditendial => $request->username,
					'password' => $request->password,
					'block' => '0'
				], $request->filled('remember'));
			}
		}

		return false;
	}

	private function username()
	{
		return 'username';
	}

	private function email()
	{
		return 'email';
	}

	public function logout(Request $request)
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		return redirect('login');
	}
}