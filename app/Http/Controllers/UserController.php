<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class UserController extends Controller
{
	public function checkUserName(Request $request)
	{
		$user = User::where($request->field, $request->q)->first();

		if ($user) {
			return response()->json(['valid' => false]);
		}

		return response()->json(['valid' => true]);
	}
}