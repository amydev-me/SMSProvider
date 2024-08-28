<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\UserBalance;

use App\Models\UserPackage;
use App\Models\UserSetting;

use App\Models\AdminToken;
use App\Models\LogDetail;

use App\Models\Country;
use App\Models\Contact;
use App\Models\SmsLog;
use App\Models\Sender;
use App\Models\User;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Mail;

trait SendMessage
{
	use SmsHelper;

	protected function sendMessageToOperator(Request $request)
	{
		$sms_log = $this->createSmsLog( $request->all() );

		if ($sms_log) {

			$default_sender = $this->getDefaultSender();

			$sender_id = $this->getSenderId($request);
			$operator_ids = NULL;
			$foreign_sender = NULL;

			if ($sender_id != NULL) {
				$operator_ids = $sender_id->sender_details->pluck('operator_id')->toArray();
				$foreign_sender = $sender_id->sender_details->where('operator_id', NULL)->where('foreign', 1)->first();
			}

			$phones = $request->to;

			foreach ($phones as $phone) {

				$country = $phone['country'];
				$operator = $phone['operator'];
				$telecom = $phone['telecom'];

				if ( $operator != NULL ) {
					$operator_id = $operator->id;
					$operator_name = $operator->name;
				} else {
					$operator_id = null;
					$operator_name = 'unknown';
				}

				try {

					if ( $country->code == 'MM' ) {
						$sender_name = $this->getSenderName($default_sender, $operator, $sender_id, $operator_ids, $country->code);
					}
					else {
						$sender_name = $this->getSenderName($default_sender, $operator, $sender_id, $foreign_sender, $country->code);
					}

					$telecom_controller = $this->getTelecomController($telecom->name);
					$_class = new $telecom_controller($telecom, $operator_name, $phone['message_parts'], $country);

					$response = $_class->sendMessage($sender_name, $phone['remove_plus'], $phone['body'], $phone['long_message']);

					$this->createLogDetail($sms_log->id, $phone['formatted_number'], $country->id, $response['message_id'], $operator_id, $operator_name, $phone['message_parts'], 'Delivered', $request->source);


				} catch (\Exception $e) {
					Log::critical($e);
					$this->createLogDetail($sms_log->id, $phone['formatted_number'], $country->id, null, $operator_id, $operator_name, $phone['message_parts'], 'Failed', $request->source);
				}
			}

			if ( isset($request->wrong_numbers) ) {
				foreach($request->wrong_numbers as $phone) {
					$this->createLogDetail($sms_log->id, $phone, null, null, null, 'unknown', 0, 'Rejected', $request->source);
				}
			}

			if ( isset($request->user_id) && $request->user_id != NULL ) {
				$user = User::with('user_setting')->where('id', $request->user_id)->first();

				if ($user->user_setting->sent == '0' && $user->sms_type != 'PAYG') {
					$this->sendRemainingBalance($user);
				}
			}

			return response()->json($this->responseSmsLogs($sms_log->id), 200);
		}

		return response()->json($this->getResponseMessage(01), 400);
	}

	protected function createSmsLog(array $data)
	{
		$params = [
			'batch_id'			=> Carbon::now()->timestamp,
			'sender_name'		=> $data['sender_name'],
			'message_content'	=> $data['body'],
			'message_parts'		=> $data['message_parts'],
			'encoding'			=> $data['encoding'],
			'total_sms'			=> $data['total_sms'],
			'total_credit'		=> $data['total_credit'],
			'total_characters'	=> $data['total_characters'],
			'source'			=> $data['source'],
			'create_sms'		=> Carbon::now()->timezone('Asia/Yangon')
		];

		if ( isset($data['user_id']) && $data['user_id'] != NULL ) {
			$params['user_id']	= $data['user_id'];
			$params['type']		= 'user';
			$params['sms_type']	= $data['sms_type'];
		} else {
			$params['admin_id']	= $data['admin_id'];
			$params['type']		= 'admin';
		}

		return SmsLog::create($params);
	}

	protected function createLogDetail($sms_log_id, $recipient, $country_id, $message_id, $operator_id, $operator_name, $total_usage, $status, $source)
	{
		LogDetail::create([
			'sms_log_id'	=> $sms_log_id,
			'message_id'	=> $message_id,
			'recipient'		=> $recipient,
			'country_id'	=> $country_id,
			'operator_id'	=> $operator_id,
			'operator'		=> $operator_name,
			'total_usage'	=> $total_usage,
			'status'		=> $status,
			'source'		=> $source,
			'send_at'		=> Carbon::now()->timezone('Asia/Rangoon')
		]);
	}

	protected function responseSmsLogs($sms_log_id)
	{
		$data = new \stdClass();

		$logs = SmsLog::with('log_details')->where('id', $sms_log_id)->first();

		$data->code				= 200;
		$data->message			= 'Success';
		$data->description		= 'Success';
		$data->status			= 'success';
		$data->batchId			= $logs->batch_id;
		$data->encoding			= $logs->encoding;
		$data->message_content	= $logs->message_content;
		$data->total_characters	= $logs->total_characters;
		$data->message_parts	= $logs->message_parts;
		$data->total_credit		= $logs->total_credit;
		$data->total_sms		= $logs->total_sms;
		$data->source			= $logs->source;

		$details				= $logs->log_details()->select(['id as messageId', 'recipient', 'operator', 'status', 'send_at as send_at'])->get();		
		$data->data				= $details;

		return $data;
	}

	protected function getSenderId($request)
	{
		$senders = Sender::with('sender_details');

		if ( isset($request->user_id) && $request->user_id != NULL ) {
			$senders->whereHas('sender_users', function ($query) use ($request) {
						$query->where('user_id', $request->user_id);
					});
		}

		$senders = $senders->get();
		$sender_id = NULL;

		if (count($senders) > 0) {
			foreach ($senders as $key) {
				if ($key->sender_name == $request->sender_name) {
					$sender_id = $key;
				}
			}
		}

		return $sender_id;
	}

	private function getSenderName($sender_name, $operator, $sender_id, $operator_ids, $country_code)
	{
		if ($country_code == 'MM') {
			if ($operator_ids != NULL) {
				if ( in_array($operator->id, $operator_ids) ) {
					$sender_name = $sender_id->sender_name;
				}
			}
		} else {
			if ($operator_ids != NULL) {
				$sender_name = $sender_id->sender_name;
			}
		}

		return str_replace(' ', '', $sender_name);
	}

	private function getTelecomController($telecom_name)
	{
		$telecom_path = '\App\Http\Controllers\Telecom';
		$controller = ucfirst( strtolower($telecom_name) ) . 'Controller';

		return $telecom_path . '\\' . $controller;
	}

	private function sendRemainingBalance($user)
	{
		if ($user->sms_type == 'Package') {
			$remaining_credits = $this->credits($user->id);
			$currency = 'credit';
		} else {
			$remaining_credits = $user->usd_credits();
			$currency = 'USD';
		}

		$minimum_credit = $user->user_setting->minimum_credit;

		$text = 'Your remaining ' . $currency . ' balance is ' . $remaining_credits . '. You are receiving this email because you set your minimum balance to ' . $minimum_credit . '. ';

		if ($user->sms_type == 'Package') {
			$text .= 'You can buy new credit packages at <a href="https://triplesms.com/buy">triplesms.com</a>.';
		} else {
			$text .= 'Please contact our customer support to buy more USD credits.';
		}

		if ($remaining_credits <= $minimum_credit) {
			if ($user->user_setting->credit_email_alert == '1') {
				Mail::send('admin-mail.newsletter', compact('text'), function($message) use ($user) {
					$message->to($user->email)
							->subject('Remaining Balance Notice');
				});
			}

			if ($user->user_setting->credit_sms_alert == '1') {
				$client = new Client();

				try {
					$token = AdminToken::first()->api_secret;

					$client->request(
						'POST', 'https://triplesms.com/api/send/message', [
							'headers' => [
								'Authorization' => "Bearer {$token}"
							],

							'json' => [
								'sender' => 'TripleSMS',
								'to' => $user->mobile,
								'body' => 'Your remaining ' . $currency . ' balance is ' . $remaining_credits . '.'
							]
						]
					);
				} catch (RequestException $e) {
					throw $e;
				}
			}

			UserSetting::where('id', $user->user_setting->id)
						->update(['credit_email_alert' => '0', 'credit_sms_alert' => '0', 'sent' => '1']);
		}
	}

	protected function credits($user_id)
	{
		return UserBalance::where('user_id', $user_id)->value('balance');

		// return UserPackage::where('user_id', $user_id)->where('status', 'paid')->sum('total_credit') - $this->usage($user_id);
	}

	private function usage($user_id)
	{
		return SmsLog::where('user_id', $user_id)->sum('total_credit');
	}
}