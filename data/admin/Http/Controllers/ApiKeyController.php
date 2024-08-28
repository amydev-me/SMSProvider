<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Web\Http\Controllers\TokenGenerate;
use App\Models\AdminToken;
use App\Models\Admin;
use Validator;

class ApiKeyController extends Controller
{
	public function index()
	{
		return view('admin-views.api-keys');
	}

	public function getTokens()
	{
		$tokens = Auth::guard('admin')->user()->admin_tokens;
		return response()->json($tokens);
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), ['app_name' => 'required']);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'errors' => 'Error occured while creating token.'], 200);
		}

		$input = $request->all();
		$input['admin_id'] = Auth::guard('admin')->user()->id;
		$input['api_key'] = Auth::guard('admin')->user()->username;
		$input['api_secret'] = (new TokenGenerate())->generateSecret();
		AdminToken::create($input);
		return response()->json(['status' => true], 200);
	}

	public function deleteToken($id)
	{
		$success = AdminToken::where('id', $id)->whereHas('admin', function($query) {
			$query->currentAdmin();
		})->firstOrFail()->delete();

		if ($success) {
			return response()->json(['status' => true], 200);
		} else {
			return response()->json(['status' => false], 500);
		}
	}
}