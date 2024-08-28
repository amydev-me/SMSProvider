<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserToken;
use App\Models\Contact;

class GetContacts extends Controller
{
	public function getContacts(Request $request)
	{
		$user = UserToken::where('api_secret', $request->bearerToken())->first();

		if ($user) {
			$contacts = Contact::where('user_id', $user->user_id)
								->select('contactName AS name', 'email', 'companyName AS company', 'birthdate', 'mobile', 'address');

			$limit = $request->limit ? $request->limit : 10;

			$response = [
					'code' => 200,
					'message' => 'Success',
					'description' => 'Success',
					'status' => 'success',
					'contacts' => $contacts->paginate($limit)
				];

			return response()->json($response, 200);
		}

		return ['code' => 01, 'message' => 'Invalid request.', 'description' => 'A request containing invalid parameters or invalid data', 'status' => 'failed'];
	}
}