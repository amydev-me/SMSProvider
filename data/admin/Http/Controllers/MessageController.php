<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\SendMessage;
use App\Http\Controllers\SmsHelper;

use App\Models\ScheduleMessage;
use App\Models\ScheduleDetail;
use App\Models\Country;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Phlib\SmsLength\SmsLength;
use Carbon\Carbon;
use Validator;

class MessageController extends Controller
{
	use SendMessage;

	public function compose()
	{
		return view('admin-views.compose');
	}

	public function sendSms(Request $request)
	{
		$validator = $this->validateSms($request->all());
		if ( $validator->fails() ) {
			return response()->json($this->getResponseMessage(01), 400);
		}

		if ($request->sender) {
			$check_sender = $this->checkAdminSender($request);
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

		$numbers = $this->getValidNumbers($contacts, $body);

		$request['admin_id']			= Auth::guard('admin')->user()->id;
		$request['message_parts']		= $message_parts;
		$request['encoding']			= $sms_length->getEncoding() == 'ucs-2' ? 'Unicode' : 'Plain Text';

		$request['total_sms']			= $message_parts * count( $numbers['valid_numbers'] );
		$request['total_credit']		= $numbers['total_credit'];
		$request['total_characters']	= $sms_length->getSize();

		$request['source']				= $request->source;
		$request['sender_name']			= $request->sender_name ? $request->sender_name : $this->getDefaultSender();

		$request['to']					= $numbers['valid_numbers'];
		$request['wrong_numbers']		= $numbers['wrong_numbers'];

		return $this->sendMessageToOperator($request);
	}

	public function createMessageSchedule(Request $request)
	{
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

			$numbers = $this->getValidNumbers($contacts, $body);

			$request['admin_id']			= Auth::guard('admin')->user()->id;
			$request['message_content']		= $body;
			$request['message_parts']		= $message_parts;
			$request['encoding']			= $sms_length->getEncoding() == 'ucs-2' ? 'Unicode' : 'Plain Text';

			$request['total_sms']			= $message_parts * count( $numbers['valid_numbers'] );
			$request['total_credit']		= $numbers['total_credit'];
			$request['total_characters']	= $sms_length->getSize();

			$request['sender_name']			= $request->sender ? $request->sender : $this->getDefaultSender();
			$request['to']					= $numbers['valid_numbers'];

			$request['send_at']				= Carbon::parse($request->send_at, $request->timezone)->timezone('UTC')->setTimezone('Asia/Rangoon');
			$request['utc_timezone']		= $request->timezone;
			$request['status']				= 'Waiting';

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