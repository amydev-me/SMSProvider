<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\UserToken;
use App\Models\Contact;

use Auth;

class GetBalance extends Controller
{
	public function getBalance(Request $request)
	{
		$user = UserToken::where('api_secret', $request->bearerToken())->first();

		if ($user) {
			$balance = Auth::guard('web')->user()->credits();

			$response = [
					'code' => 200,
					'message' => 'Success',
					'description' => 'Success',
					'status' => 'success',
					'balance' => $balance
				];

			return response()->json($response, 200);
		}

		return ['code' => 01, 'message' => 'Invalid request.', 'description' => 'A request containing invalid parameters or invalid data', 'status' => 'failed'];
	}
}