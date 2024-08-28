<?php

namespace User\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SendMessage;

use App\Models\ScheduleMessage;
use App\Models\ScheduleDetail;

use App\Models\UserBalance;
use App\Models\User;

use Phlib\SmsLength\SmsLength;
use Carbon\Carbon;
use Auth;

class SendMessageController extends Controller
{
	use SendMessage;

	public function sendSms(Request $request)
	{
		$isVerify = User::CurrentUser()->where('verified', true)->exists();
		if ( !$isVerify ) {
			return response()->json($this->getResponseMessage(04), 400);
		}

		$validator = $this->validateSms($request->all());
		if ( $validator->fails() ) {
			return response()->json($this->getResponseMessage(01), 400);
		}

		if ($request->sender) {
			$check_sender = $this->checkUserSender($request);
			if ( !$check_sender ) {
				return response()->json($this->getResponseMessage(06), 400);
			}
		}

		if ($request->message_parts > 6) {
			return response()->json($this->getResponseMessage(02), 400);
		}

		$sms_length = new SmsLength($request->body);
		$message_parts = $sms_length->getMessageCount();

		$contacts = $this->makeWebContacts($request->to);

		if (count($contacts) > 1000) {
			return response()->json($this->getResponseMessage(07), 413);
		}

		$body = trim( $this->removeEmoji($request->body) );

		$numbers = $this->getValidNumbers($contacts, $body, Auth::guard('web')->user()->id);

		$request['user_id']				= Auth::guard('web')->user()->id;
		$request['message_parts']		= $message_parts;
		$request['encoding']			= $sms_length->getEncoding() == 'ucs-2' ? 'Unicode' : 'Plain Text';

		$request['total_sms']			= $numbers['total_sms'];
		$request['total_credit']		= $numbers['total_credit'];
		$request['total_characters']	= $sms_length->getSize();

		$request['source']				= $request->source;
		$request['sms_type']			= Auth::guard('web')->user()->sms_type;
		$request['sender_name']			= $request->sender_name ? $request->sender_name : $this->getDefaultSender();

		$request['to']					= $numbers['valid_numbers'];
		$request['wrong_numbers']		= $numbers['wrong_numbers'];

		if ( Auth::guard('web')->user()->sms_type == 'Package' ) {
			if ( Auth::guard('web')->user()->credits() < $numbers['total_credit'] ) {
				return response()->json($this->getResponseMessage(03), 403);
			}

			UserBalance::where('user_id', Auth::guard('web')->user()->id)->decrement('balance', $numbers['total_credit']);
		}

		return $this->sendMessageToOperator($request);
	}

	public function createMessageSchedule(Request $request)
	{
		$isVerify = User::CurrentUser()->where('verified', true)->exists();
		if ( !$isVerify ) {
			return response()->json($this->getResponseMessage(04), 400);
		}

		$validator = $this->validateSms($request->all());
		if ( $validator->fails() ) {
			return response()->json($this->getResponseMessage(01), 400);
		}

		if ($request->sender) {
			$check_sender = $this->checkUserSender($request);
			if ( !$check_sender ) {
				return response()->json($this->getResponseMessage(06), 400);
			}
		}

		if ($request->message_parts > 6) {
			return response()->json($this->getResponseMessage(02), 400);
		}

		try {
			$sms_length = new SmsLength($request->body);
			$message_parts = $sms_length->getMessageCount();

			$contacts = $this->makeWebContacts($request->to);

			if (count($contacts) > 1000) {
				return response()->json($this->getResponseMessage(07), 413);
			}

			$body = trim( $this->removeEmoji($request->body) );

			$numbers = $this->getValidNumbers($contacts, $body, Auth::guard('web')->user()->id);

			$request['user_id']				= Auth::guard('web')->user()->id;
			$request['message_content']		= $body;
			$request['message_parts']		= $message_parts;
			$request['encoding']			= $sms_length->getEncoding() == 'ucs-2' ? 'Unicode' : 'Plain Text';

			$request['total_sms']			= $message_parts * count( $numbers['valid_numbers'] );
			$request['total_credit']		= $numbers['total_credit'];
			$request['total_characters']	= $sms_length->getSize();

			$request['sms_type']			= Auth::guard('web')->user()->sms_type;
			$request['sender_name']			= $request->sender ? $request->sender : $this->getDefaultSender();
			$request['to']					= $numbers['valid_numbers'];

			$request['send_at']				= Carbon::parse($request->send_at, $request->timezone)->timezone('UTC')->setTimezone('Asia/Rangoon');
			$request['utc_timezone']		= $request->timezone;
			$request['status']				= 'Waiting';

			if ( Auth::guard('web')->user()->sms_type == 'Package' ) {
				if ( Auth::guard('web')->user()->credits() < $numbers['total_credit'] ) {
					return response()->json($this->getResponseMessage(03), 403);
				}
			}

			$message = ScheduleMessage::create($request->except('body', 'to'));

			foreach ($numbers['valid_numbers'] as $phone) {
				if ($phone['operator'] != NULL) {
					$operator_name = $phone['operator']->name;
				} else {
					$operator_name = 'unknown';
				}

				ScheduleDetail::create([
					'schedule_message_id'	=> $message->id,
					'recipient'				=> $phone['formatted_number'],
					'country'				=> $phone['country']->name,
					'operator'				=> $operator_name,
					'source'				=> $request->source,
					'total_usage'			=> $phone['message_parts']
				]);
			}

			return response()->json($this->getResponseMessage(200), 200);

		} catch (\Exception $e) {
			return response()->json($this->getResponseMessage(01), 400);
		}
	}
}


		/*else {
			if (Auth::guard('web')->user()->usd_credits() < $request['total_sms'] * Auth::guard('web')->user()->usd_rate) {
				return response()->json($this->getResponseMessage(05), 403);
			}
		}*/