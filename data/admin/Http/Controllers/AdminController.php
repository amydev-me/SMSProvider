<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin;
use Carbon\Carbon;
use Validator;
use Hash;

class AdminController extends Controller
{
	public function getAdmins()
	{
		$admins = Admin::where('obsolete', '<>', '1')
						->where('username', '<>', 'logadmin')
						->paginate(10);
		return response()->json($admins);
	}

	public function search($name)
	{
		$admins = Admin::where('obsolete', '<>', '1')
						->where('username', 'LIKE', '%' . $name . '%')
						->orWhere('full_name', 'LIKE', '%' . $name . '%')
						->paginate(10);
		return response()->json($admins);
	}

	public function create(Request $request)
	{
		$validator = self::validateAdmin($request->all());

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check_username = Admin::where('username', $request->username)->where('obsolete', '0')->first();
		if ($check_username) {
			return response()->json(['status' => false, 'message' => ['username' => 'The username has already been taken.']], 200);
		}

		$admin = self::insert($request->all());

		return response()->json(['status' => true, 'admin' => $admin], 200);
	}

	public static function validateAdmin($request)
	{
		return Validator::make($request, [
			'username' => 'required|max:30',
			'full_name' => 'required',
			'password' => 'required',
			'role' => 'required'
		]);
	}

	public static function insert($params)
	{
		return Admin::create([
			'username' => $params['username'],
			'password' => Hash::make($params['password']),
			'full_name' => $params['full_name'],
			'role' => $params['role']
		]);
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'username' => 'required|max:30',
			'full_name' => 'required',
			'role' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check_username = Admin::where('username', $request->username)->where('id', '<>', $request->id)->where('obsolete', '0')->first();
		if ($check_username) {
			return response()->json(['status' => false, 'message' => ['username' => 'The username has already been taken.']], 200);
		}

		$admin = Admin::where('id', $request->id)->first();

		if ($admin) {
			$params['username'] = $request->username;
			$params['full_name'] = $request->full_name;
			$params['role'] = $request->role;

			$admin->update($params);
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function delete($id)
	{
		$admin = Admin::where('id', $id)->first();

		if ($admin) {
			$admin->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false, 'message' => "Can't access data."]);
	}

	public function checkOldPassword(Request $request)
	{
		if ( strpos(request()->headers->get('referer'), 'dashboard-user') ) {
			$admin = Admin::where('id', Auth::guard('log')->user()->id)->first();
		} else {
			$admin = Admin::where('id', Auth::guard('admin')->user()->id)->first();
		}

		if ( Hash::check($request->old_password, $admin->password) ) {
			return response()->json(['status' => true], 200);
		} else {
			return response()->json(['status' => false], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

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

		if ( strpos(request()->headers->get('referer'), 'dashboard-user') ) {
			$admin = Admin::where('id', Auth::guard('log')->user()->id)->first();

			if ($admin) {
				Auth::guard('log')->user()->update([ 'password' => Hash::make($request->new_password) ]);
				return response()->json(['status' => true], 200);
			}
		} else {
			$admin = Admin::where('id', Auth::guard('admin')->user()->id)->first();

			if ($admin) {
				Auth::guard('admin')->user()->update([ 'password' => Hash::make($request->new_password) ]);
				return response()->json(['status' => true], 200);
			}
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}
}