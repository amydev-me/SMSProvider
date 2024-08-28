<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\SendMessage;
use App\Http\Controllers\SmsHelper;

use App\Models\UserBalance;

use Phlib\SmsLength\SmsLength;
use Lcobucci\JWT\Parser;
use Auth;

class UserSendSms extends Controller
{
	use SendMessage;

	public function sendSms(Request $request)
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

		$numbers = $this->getValidNumbers($contacts, $body, Auth::guard('web')->user()->id);

		$request['user_id']				= Auth::guard('web')->user()->id;
		$request['message_parts']		= $message_parts;
		$request['encoding']			= $sms_length->getEncoding() == 'ucs-2' ? 'Unicode' : 'Plain Text';

		$request['total_sms']			= $numbers['total_sms'];
		$request['total_credit']		= $numbers['total_credit'];
		$request['total_characters']	= $sms_length->getSize();

		$request['source']				= 'Api';
		$request['sms_type']			= Auth::guard('web')->user()->sms_type;
		$request['sender_name']			= $request->sender ? $request->sender : $this->getDefaultSender();

		$request['to']					= $numbers['valid_numbers'];
		$request['wrong_numbers']		= $numbers['wrong_numbers'];

		if ( Auth::guard('web')->user()->sms_type == 'Package' ) {
			if ( Auth::guard('web')->user()->credits() < $numbers['total_credit'] ) {
				return response()->json($this->getResponseMessage(03), 403);
			}

			UserBalance::where('user_id', Auth::guard('web')->user()->id)->decrement('balance', $numbers['total_credit']);
		}

		$response = $this->sendMessageToOperator($request);

		$token = $request->token;
		$access_id = (new Parser())->parse($token)->getHeader('jti');
		$access_token = Auth::guard('web')->user()->tokens->find($access_id);
		$access_token->delete();

		return $response;
	}
}