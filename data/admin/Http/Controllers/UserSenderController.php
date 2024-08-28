<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserSender;
use Carbon\Carbon;
use Validator;
use DB;

class UserSenderController extends Controller
{
	public function getSenders()
	{
		$senders = UserSender::with('user', 'operator')
							->select('id', 'user_id', 'sender_name', 'operator_id', DB::raw('DATE_FORMAT(register_at, "%d %b %Y") as register_at'))
							->paginate(10);
		return response()->json($senders);
	}

	public function search($name)
	{
		$senders = UserSender::with('user', 'operator')
							->whereHas('user', function ($query) use ($name) {
								$query->where('username', 'LIKE', '%' . $name . '%');
							})
							->orWhereHas('operator', function ($query) use ($name) {
								$query->where('name', 'LIKE', '%' . $name . '%');
							})
							->orWhere('sender_name', 'LIKE', '%' . $name . '%')
							->select('id', 'user_id', 'sender_name', 'operator_id', DB::raw('DATE_FORMAT(register_at, "%d %b %Y") as register_at'))
							->paginate(10);
		return response()->json($senders);
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'sender_name' => 'required',
				'user_id' => 'required',
				'register_at' => 'required|date',
			]
		);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = UserSender::where('user_id', $request->user_id)
							->where('operator_id', $request->operator_id)
							->where('sender_name', $request->sender_name)
							->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['exist' => 'Operator already existed for this user.']], 200);
		}

		$request['register_at'] = Carbon::parse($request->register_at)->timezone('Asia/Yangon')->format('Y-m-d');

		if ($request->operator_id == NULL) {
			$request['foreign'] = 1;
		} else {
			$request['foreign'] = 0;
		}

		$sender = UserSender::create($request->all());

		if ($sender) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'sender_name' => 'required',
				'user_id' => 'required',
				'register_at' => 'required|date',
			]
		);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = UserSender::where('user_id', $request->user_id)
							->where('operator_id', $request->operator_id)
							->where('sender_name', $request->sender_name)
							->where('id', '<>', $request->id)
							->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['exist' => 'Operator already existed for this user.']], 200);
		}

		$sender = UserSender::where('id', $request->id)->first();
		if ($sender) {
			$request['register_at'] = Carbon::parse($request->register_at)->timezone('Asia/Yangon')->format('Y-m-d');

			if ($request->operator_id == NULL) {
				$request['foreign'] = 1;
			} else {
				$request['foreign'] = 0;
			}

			$sender->update($request->except('id'));

			return response()->json(['success' => true]);
		}
		
		return response()->json(['success' => false]);
	}

	public function delete($id)
	{
		$sender = UserSender::where('id', $id)->first();
		if ($sender) {
			$sender->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}
}