<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SenderDetail;
use App\Models\SenderUser;
use App\Models\Sender;

use Carbon\Carbon;
use Validator;
use DB;

class SenderController extends Controller
{
	public function getSenders()
	{
		$senders = Sender::with('sender_details', 'sender_users')->paginate(10);
		return response()->json($senders);
	}

	public function search($name)
	{
		$senders = Sender::with('sender_details')->where('sender_name', 'LIKE', '%' . $name . '%')->paginate(10);
		return response()->json($senders);
	}

	public function create(Request $request)
	{
		$validator = $this->validateSender($request->all());
		if ( $validator->fails() ) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$sender = Sender::create($request->all());
		if ($sender) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function update(Request $request)
	{
		$validator = $this->validateSender($request->all());
		if ( $validator->fails() ) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$sender = Sender::where('id', $request->id)->first();
		if ($sender) {
			$sender->update($request->except('id'));
			return response()->json(['success' => true]);
		}
		
		return response()->json(['success' => false]);
	}

	private function validateSender($request)
	{
		return Validator::make($request, [
				'sender_name' => 'required'
			]
		);
	}

	public function delete(Request $request)
	{
		$sender = Sender::where('id', $request->id)->first();
		if ($sender) {
			$sender->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	/* ---------------------------------------------------------------------- */

	public function getSenderName($id)
	{
		return Sender::where('id', $id)->value('sender_name');
	}

	public function getSenderDetails(Request $request)
	{
		$sender_details = SenderDetail::with('operator')
									->where('sender_id', $request->sender_id)
									->select('id', 'sender_id', 'operator_id', DB::raw('DATE_FORMAT(register_at, "%d %b %Y") as register_at'))
									->paginate(10);
		return response()->json($sender_details);
	}

	public function searchSenderDetails(Request $request, $name)
	{
		$sender_details = SenderDetail::with('operator')
									->whereHas('operator', function ($query) use ($name) {
										$query->where('name', 'LIKE', '%' . $name . '%');
									})
									->where('sender_id', $request->sender_id)
									->select('id', 'sender_id', 'operator_id', DB::raw('DATE_FORMAT(register_at, "%d %b %Y") as register_at'))
									->paginate(10);
		return response()->json($sender_details);
	}

	public function createSenderDetail(Request $request)
	{
		$validator = $this->validateSenderDetail($request->all());
		if ( $validator->fails() ) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = $this->checkSenderDetail($request->all());
		if ($check) {
			return response()->json(['status' => false, 'message' => ['exist' => 'Operator already existed for this sender ID.']], 200);
		}

		$request = $this->senderDetailData($request);
		$sender_detail = SenderDetail::create($request->all());

		if ($sender_detail) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function updateSenderDetail(Request $request)
	{
		$validator = $this->validateSenderDetail($request->all());
		if ( $validator->fails() ) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = $this->checkSenderDetail($request->all(), TRUE);
		if ($check) {
			return response()->json(['status' => false, 'message' => ['exist' => 'Operator already existed for this sender ID.']], 200);
		}

		$sender_detail = SenderDetail::where('id', $request->id)->first();
		if ($sender_detail) {
			$request = $this->senderDetailData($request);
			$sender_detail->update($request->except('id'));

			return response()->json(['success' => true]);
		}
		
		return response()->json(['success' => false]);
	}

	private function validateSenderDetail($request)
	{
		return Validator::make($request, [
				'sender_id' => 'required',
				'register_at' => 'required|date'
			]
		);
	}

	private function checkSenderDetail($request, $id_check = FALSE)
	{
		$check = SenderDetail::where('sender_id', $request['sender_id'])
							->where('operator_id', $request['operator_id']);

		if ($id_check == TRUE) {
			$check->where('id', '<>', $request['id']);
		}

		return $check->first();
	}

	private function senderDetailData($request)
	{
		$request['register_at'] = Carbon::parse($request->register_at)->timezone('Asia/Yangon')->format('Y-m-d');

		if ($request->operator_id == NULL) {
			$request['foreign'] = 1;
		} else {
			$request['foreign'] = 0;
		}

		return $request;
	}

	public function deleteSenderDetail(Request $request)
	{
		$sender_detail = SenderDetail::where('id', $request->id)->first();
		if ($sender_detail) {
			$sender_detail->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	/* ---------------------------------------------------------------------- */

	public function getUsers($sender_id)
	{
		$users = SenderUser::with('user')->where('sender_id', $sender_id)->get();
		return $users;
	}

	public function createSenderUser(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'sender_id' => 'required',
				'user_id' => 'required'
			]
		);

		if ( $validator->fails() ) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = $this->checkSenderUser($request->all());
		if ($check) {
			return response()->json(['status' => false, 'message' => ['exist' => 'User already existed for this sender ID.']], 200);
		}

		$sender_user = SenderUser::create($request->all());
		if ($sender_user) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	private function checkSenderUser($request, $id_check = FALSE)
	{
		$check = SenderUser::where('sender_id', $request['sender_id'])
							->where('user_id', $request['user_id']);

		if ($id_check == TRUE) {
			$check->where('id', '<>', $request['id']);
		}

		return $check->first();
	}

	public function deleteSenderUser(Request $request)
	{
		$sender_user = SenderUser::where('id', $request->id)->first();
		if ($sender_user) {
			$sender_user->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}
}