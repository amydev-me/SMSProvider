<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\SendMessage;
use App\Http\Controllers\SmsHelper;

use Phlib\SmsLength\SmsLength;
use Lcobucci\JWT\Parser;
use Auth;

class AdminSendSms
{
	use SendMessage;

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

		$sms_length = new SmsLength($request->body);
		$message_parts = $sms_length->getMessageCount();

		if ($message_parts > 6) {
			return response()->json($this->getResponseMessage(02), 400);
		}

		$contacts = $this->makeApiContacts($request->to);

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

		$request['source']				= 'Api';
		$request['sender_name']			= $request->sender ? $request->sender : $this->getDefaultSender();

		$request['to']					= $numbers['valid_numbers'];
		$request['wrong_numbers']		= $numbers['wrong_numbers'];

		return $this->sendMessageToOperator($request);
	}
}