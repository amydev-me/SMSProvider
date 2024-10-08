<?php

namespace App\Http\Controllers;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Validation\Rule;
use Phlib\SmsLength\SmsLength;

use App\Models\DefaultSetting;

use App\Models\Gateway;
use App\Models\Telecom;

use App\Models\Operator;
use App\Models\UserRate;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Sender;

use Validator;
use Auth;

trait SmsHelper
{
	protected function getResponseMessage($code, $description = NULL)
	{
		switch ($code) {
			case 01:
				return ['status' => 'failed', 'code' => '01', 'message' => 'Invalid Request', 'description' => 'A request containing invalid parameters or invalid data.'];
				break;

			case 02:
				return ['status' => 'failed', 'code' => '02', 'message' => 'Not Allowed', 'description' => 'Operator only allowed 6 SMS.'];
				break;

			case 03:
				return ['status' => 'failed', 'code' => '03', 'message' => 'Insufficient  Balance', 'description' => 'You need to purchase more Packages before you can create message.'];
				break;

			case 04:
				return ['status' => 'failed', 'code' => '04', 'message' => 'Account Verification', 'description' => 'Verify your email address first.'];
				break;

			case 05:
				return ['status' => 'failed', 'code' => '05', 'message' => 'Insufficient  Balance', 'description' => 'You need to purchase more USD before you can create message.'];
				break;

			case 06:
				return ['status' => 'failed', 'code' => '06', 'message' => 'Not Acceptable', 'description' => 'You cannot use this sender ID.'];
				break;

			case 07:
				return ['status' => 'failed', 'code' => '07', 'message' => 'Too Large', 'description' => 'You can only send 1000 numbers per time.'];
				break;

			case 401:
				return ['status' => 'failed', 'code' => '401', 'message' => 'Unauthorized', 'description' => $description];
				break;

			case 403:
				return ['status' => 'failed', 'code' => '403', 'message' => 'Forbidden', 'description' => $description];
				break;

			case 200:
				return ['status' => 'success', 'code' => 200, 'message' => 'Success', 'description' => 'Success'];
				break;
		}
	}

	protected function validateSms($request)
	{
		return Validator::make($request, [
			'sender' => 'nullable',
			'body' => 'required',
			'to' => 'required'
		]);
	}

	protected function checkUserSender($request)
	{
		return Sender::where('sender_name', $request['sender'])
					->whereHas('sender_users', function ($query) use ($request) {
						$query->where('user_id', Auth::guard('web')->user()->id);
					})
					->first();
	}

	protected function checkAdminSender($request)
	{
		return Sender::where('sender_name', $request['sender'])->first();
	}

	protected function makeApiContacts($numbers)
	{
		$contacts = [];

		if ( !is_array($numbers) ) {
			array_push($contacts, $numbers);
		} else {
			$contacts = $numbers;
		}

		return array_values(array_filter($contacts));
	}

	protected function makeWebContacts($receipients)
	{
		$contacts = array();
		$receipients = json_decode($receipients);

		foreach ($receipients as $rec) {
			if ( !isset($rec->id) ) {
				array_push($contacts, $rec->text);
			} else {
				if ($rec->type == 'list') {
					$group_id = $rec->id;

					$_contacts = Contact::currentUser()
										->whereHas('groups', function ($q) use ($group_id) {
											$q->where('id', $group_id);
										})
										->get()
										->pluck('mobile')
										->toArray();

					foreach ($_contacts as $cont) {
						array_push($contacts, $cont);
					}
				} else {
					array_push($contacts, $rec->label);
				}
			}
		}

		return array_values(array_filter($contacts));
	}

	protected function getCountries()
	{
		$countries = Country::where('status', '1')
							->where('rate', '>', '0')
							->get();

		return $countries;
	}

	private function getCountryByCode($code)
	{
		$country = Country::where('code', $code)
							->first();

		return $country;
	}

	protected function getValidNumbers($contacts, $body, $user_id = NULL)
	{
		$i = 0;
		$total_sms = 0;
		$total_credit = 0;
		$valid_numbers = [];

		$countries = $this->getCountries();
		$country_codes = $countries->pluck('code')->toArray();

		foreach ($contacts as $phone) {

			if ( substr($phone, 0, 1) != '+' ) {
				$phone = '+' . $phone;
			}

			if ( substr($phone, 0, 1) == '+' ) {

				$validator = Validator::make(['phone' => $phone], [
					'phone' => Rule::phone()->country( $country_codes )
				]);

				if ( !$validator->fails() ) {
					$formatted_number = PhoneNumber::make($phone);
					$remove_plus = str_replace('+', '', $formatted_number);

					$country = $this->getCountryByCode($formatted_number->getCountry());

					$formatted_body = $this->formatBody($body, $formatted_number, $user_id);

					$sms_length = new SmsLength($formatted_body);
					$message_parts = $sms_length->getMessageCount();
					$encoding = $sms_length->getEncoding() == 'ucs-2' ? 'Unicode' : 'Plain Text';

					$total_sms += $message_parts;

					if ($user_id != NULL) {
						$rate = $this->getUserRate($user_id, $country) * $message_parts;
						$total_credit += $rate;
					}

					$long_message = FALSE;

					if ($country->prefix == '95') {
						$operator = $this->getMyanmarOperator($remove_plus);

						if ( $message_parts > 1 && ($operator->name == 'Ooredoo' || $operator->name == 'Telenor') ) {
							$long_message = TRUE;
						}

						if ( $operator->name == 'MPT' ) {
							$formatted_body = $this->removeSpecialChars($formatted_body);
						}

					} else {
						$operator = $this->getInternationalOperator($country);
					}

					$telecom = $this->getTelecom($country, $operator, $encoding);

					if ($user_id == 404) {
						$telecom = Telecom::where('id', 2)->first();
					}

					$valid_numbers[] = [
						'formatted_number'	=> $formatted_number,
						'remove_plus'		=> $remove_plus,
						'body'				=> $formatted_body,
						'message_parts'		=> $message_parts,
						'country'			=> $country->makeHidden(['created_at', 'updated_at']),
						'operator'			=> $operator,
						'long_message'		=> $long_message,
						'telecom'			=> $telecom
					];

					unset($contacts[$i]);
				}
			}

			$i++;
		}

		$wrong_numbers = array_values($contacts);

		return [
			'valid_numbers'	=> $valid_numbers,
			'wrong_numbers'	=> $wrong_numbers,
			'total_sms'		=> $total_sms,
			'total_credit'	=> $total_credit
		];
	}

	private function formatBody($body, $formatted_number, $user_id = NULL)
	{
		if ( $user_id != NULL ) {
			$contact_info = Contact::where('mobile', $formatted_number)->where('user_id', $user_id)->first();

			if ($contact_info) {
				$formatted_body = str_replace('{Contact Name}', $contact_info->contactName, $body);
			} else {
				$formatted_body = str_replace('{Contact Name}', $formatted_number, $body);
			}
		} else {
			$formatted_body = $body;
		}

		return $formatted_body;
	}

	protected function getUserRate($user_id, $country)
	{
		$user_rate = UserRate::where('user_id', $user_id)
							->where('country_id', $country->id)
							->first();

		if ($user_rate) {
			$rate = $user_rate->rate;
		} else {
			$rate = $country->rate;
		}

		return $rate;
	}

	private function getMyanmarOperator($phone)
	{
		$operator = Operator::whereHas('operator_detail', function($query) use ($phone) {
								$query->where('starting_number', substr($phone, 0, 5));
							})
							->first();

		if ($operator) {
			return $operator;
		} else {
			return Operator::where('name', 'MPT')->first();
		}
	}

	private function getInternationalOperator($country)
	{
		$operator = Operator::whereHas('operator_detail', function($query) use ($country) {
								$query->where('starting_number', 'LIKE', $country->prefix . '%');
							})
							->where('country_id', $country->id)
							->first();

		return $operator;
	}

	private function getTelecom($country, $operator, $encoding)
	{
		$gateway = Gateway::where('country_id', $country->id)
							->where('encoding', $encoding);

		if ($operator != NULL) {
			$gateway = $gateway->where('operator_id', $operator->id);
		}

		$gateway = $gateway->first();

		if ($gateway) {
			return Telecom::whereHas('default_endpoints', function ($query) use ($gateway) {
								$query->where('gateway_id', $gateway->id)
										->where('active_endpoint', 1);
							})
							->first();
		}

		return FALSE;
	}

	protected function removeEmoji($string)
	{
		return preg_replace('/[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0077}\x{E006C}\x{E0073}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0073}\x{E0063}\x{E0074}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0065}\x{E006E}\x{E0067}\x{E007F})|[\x{1F3F4}](?:\x{200D}\x{2620}\x{FE0F})|[\x{1F3F3}](?:\x{FE0F}\x{200D}\x{1F308})|[\x{0023}\x{002A}\x{0030}\x{0031}\x{0032}\x{0033}\x{0034}\x{0035}\x{0036}\x{0037}\x{0038}\x{0039}](?:\x{FE0F}\x{20E3})|[\x{1F415}](?:\x{200D}\x{1F9BA})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F468})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F468})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9BD})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9AF})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2640}\x{FE0F})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2642}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2695}\x{FE0F})|[\x{1F471}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F9CF}\x{1F647}\x{1F926}\x{1F937}\x{1F46E}\x{1F482}\x{1F477}\x{1F473}\x{1F9B8}\x{1F9B9}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F486}\x{1F487}\x{1F6B6}\x{1F9CD}\x{1F9CE}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}\x{1F9D8}](?:\x{200D}\x{2640}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B0})|[\x{1F471}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F9CF}\x{1F647}\x{1F926}\x{1F937}\x{1F46E}\x{1F482}\x{1F477}\x{1F473}\x{1F9B8}\x{1F9B9}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F486}\x{1F487}\x{1F6B6}\x{1F9CD}\x{1F9CE}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}\x{1F9D8}](?:\x{200D}\x{2642}\x{FE0F})|[\x{1F441}](?:\x{FE0F}\x{200D}\x{1F5E8}\x{FE0F})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FA}](?:\x{1F1FF})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1FA}](?:\x{1F1FE})|[\x{1F1E6}\x{1F1E8}\x{1F1F2}\x{1F1F8}](?:\x{1F1FD})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F9}\x{1F1FF}](?:\x{1F1FC})|[\x{1F1E7}\x{1F1E8}\x{1F1F1}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1FB})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1FB}](?:\x{1F1FA})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FE}](?:\x{1F1F9})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FA}\x{1F1FC}](?:\x{1F1F8})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F7})|[\x{1F1E6}\x{1F1E7}\x{1F1EC}\x{1F1EE}\x{1F1F2}](?:\x{1F1F6})|[\x{1F1E8}\x{1F1EC}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}](?:\x{1F1F5})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EE}\x{1F1EF}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1F8}\x{1F1F9}](?:\x{1F1F4})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1F3})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F4}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FF}](?:\x{1F1F2})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F1})|[\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FD}](?:\x{1F1F0})|[\x{1F1E7}\x{1F1E9}\x{1F1EB}\x{1F1F8}\x{1F1F9}](?:\x{1F1EF})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EB}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F3}\x{1F1F8}\x{1F1FB}](?:\x{1F1EE})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1ED})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1EC})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F9}\x{1F1FC}](?:\x{1F1EB})|[\x{1F1E6}\x{1F1E7}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FB}\x{1F1FE}](?:\x{1F1EA})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1E9})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FB}](?:\x{1F1E8})|[\x{1F1E7}\x{1F1EC}\x{1F1F1}\x{1F1F8}](?:\x{1F1E7})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F6}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}\x{1F1FF}](?:\x{1F1E6})|[\x{00A9}\x{00AE}\x{203C}\x{2049}\x{2122}\x{2139}\x{2194}-\x{2199}\x{21A9}-\x{21AA}\x{231A}-\x{231B}\x{2328}\x{23CF}\x{23E9}-\x{23F3}\x{23F8}-\x{23FA}\x{24C2}\x{25AA}-\x{25AB}\x{25B6}\x{25C0}\x{25FB}-\x{25FE}\x{2600}-\x{2604}\x{260E}\x{2611}\x{2614}-\x{2615}\x{2618}\x{261D}\x{2620}\x{2622}-\x{2623}\x{2626}\x{262A}\x{262E}-\x{262F}\x{2638}-\x{263A}\x{2640}\x{2642}\x{2648}-\x{2653}\x{265F}-\x{2660}\x{2663}\x{2665}-\x{2666}\x{2668}\x{267B}\x{267E}-\x{267F}\x{2692}-\x{2697}\x{2699}\x{269B}-\x{269C}\x{26A0}-\x{26A1}\x{26AA}-\x{26AB}\x{26B0}-\x{26B1}\x{26BD}-\x{26BE}\x{26C4}-\x{26C5}\x{26C8}\x{26CE}-\x{26CF}\x{26D1}\x{26D3}-\x{26D4}\x{26E9}-\x{26EA}\x{26F0}-\x{26F5}\x{26F7}-\x{26FA}\x{26FD}\x{2702}\x{2705}\x{2708}-\x{270D}\x{270F}\x{2712}\x{2714}\x{2716}\x{271D}\x{2721}\x{2728}\x{2733}-\x{2734}\x{2744}\x{2747}\x{274C}\x{274E}\x{2753}-\x{2755}\x{2757}\x{2763}-\x{2764}\x{2795}-\x{2797}\x{27A1}\x{27B0}\x{27BF}\x{2934}-\x{2935}\x{2B05}-\x{2B07}\x{2B1B}-\x{2B1C}\x{2B50}\x{2B55}\x{3030}\x{303D}\x{3297}\x{3299}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F171}\x{1F17E}-\x{1F17F}\x{1F18E}\x{1F191}-\x{1F19A}\x{1F201}-\x{1F202}\x{1F21A}\x{1F22F}\x{1F232}-\x{1F23A}\x{1F250}-\x{1F251}\x{1F300}-\x{1F321}\x{1F324}-\x{1F393}\x{1F396}-\x{1F397}\x{1F399}-\x{1F39B}\x{1F39E}-\x{1F3F0}\x{1F3F3}-\x{1F3F5}\x{1F3F7}-\x{1F3FA}\x{1F400}-\x{1F4FD}\x{1F4FF}-\x{1F53D}\x{1F549}-\x{1F54E}\x{1F550}-\x{1F567}\x{1F56F}-\x{1F570}\x{1F573}-\x{1F57A}\x{1F587}\x{1F58A}-\x{1F58D}\x{1F590}\x{1F595}-\x{1F596}\x{1F5A4}-\x{1F5A5}\x{1F5A8}\x{1F5B1}-\x{1F5B2}\x{1F5BC}\x{1F5C2}-\x{1F5C4}\x{1F5D1}-\x{1F5D3}\x{1F5DC}-\x{1F5DE}\x{1F5E1}\x{1F5E3}\x{1F5E8}\x{1F5EF}\x{1F5F3}\x{1F5FA}-\x{1F64F}\x{1F680}-\x{1F6C5}\x{1F6CB}-\x{1F6D2}\x{1F6D5}\x{1F6E0}-\x{1F6E5}\x{1F6E9}\x{1F6EB}-\x{1F6EC}\x{1F6F0}\x{1F6F3}-\x{1F6FA}\x{1F7E0}-\x{1F7EB}\x{1F90D}-\x{1F93A}\x{1F93C}-\x{1F945}\x{1F947}-\x{1F971}\x{1F973}-\x{1F976}\x{1F97A}-\x{1F9A2}\x{1F9A5}-\x{1F9AA}\x{1F9AE}-\x{1F9CA}\x{1F9CD}-\x{1F9FF}\x{1FA70}-\x{1FA73}\x{1FA78}-\x{1FA7A}\x{1FA80}-\x{1FA82}\x{1FA90}-\x{1FA95}]/u', '', $string);
	}

	protected function removeSpecialChars($string)
	{
		return preg_replace('/[!@#$%^&*()`~=|+_-]/u', '', $string);
	}

	protected function getDefaultSender()
	{
		return DefaultSetting::first()->value('sender');
	}
}