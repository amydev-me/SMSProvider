<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserToken;
use App\Models\SmsLog;

class GetMessages extends Controller
{
	public function getMessages(Request $request)
	{
		$user = UserToken::where('api_secret', $request->bearerToken())->first();

		if ($user) {
			$logs = SmsLog::where('user_id', Auth::guard('web')->user()->id)
							->select('id', 'user_id', 'sender_name', 'batch_id', 'message_content', 'message_parts', 'encoding', 'total_credit', 'total_sms', 'total_characters', 'source', 'create_sms')
							->orderBy('create_sms', 'asc');

			$limit = $request->limit ? $request->limit : 10;

			$response = [
					'code' => 200,
					'message' => 'Success',
					'description' => 'Success',
					'status' => 'success',
					'logs' => $logs->paginate($limit)
				];

			return response()->json($response, 200);
		}

		return ['code' => 01, 'message' => 'Invalid request.', 'description' => 'A request containing invalid parameters or invalid data', 'status' => 'failed'];
	}
}