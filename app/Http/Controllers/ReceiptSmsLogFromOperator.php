<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OperatorLog;
use App\Models\LogDetail;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Storage;

class ReceiptSmsLogFromOperator extends Controller
{
	// Operator log from NHN
	public function receiptLog(Request $request)
	{
//		Storage::append( 'parameter.txt', print_r($request->all(), true) );

		$operator_log = OperatorLog::create([
			'telecom_id' => 1,
			'message_id' => $request->message_id,
			'status' => $request->status,
			'destination' => $request->destination,
			'sender' => $request->sender,
			'operator_date' => $request->timestamp
		]);

		if ($operator_log) {
			$log_detail = LogDetail::where('message_id', $request->message_id)->first();

			if ($log_detail) {
				if ($request->status == 'DELIVRD') {
					$request->status = 'Delivered';
				} elseif ($request->status == 'FAILD') {
					$request->status = 'Failed';
				} elseif ($request->status == 'UNDELIV') {
					$request->status = 'Delivered';
				} elseif ($request->status == 'REJECTD') {
					$request->status = 'Operator Rejected';
				} elseif ($request->status == 'EXPIRED') {
					$request->status = 'Expired';
				}

				$log_detail->update(['status' => $request->status]);
			}
		}

		return response()->json(['status' => 'success'], 200);
	}

	// Operator log from Dexatel
	public function dexatelData(Request $request)
	{
//		Storage::append( 'parameter.txt', print_r($request->all(), true) );

		$params = str_replace( '_', '', key($request->all()) );
		$params = json_decode($params, true);

		$log_detail = LogDetail::with('sms_log')->where('message_id', $params['messageid'])->first();

		$operator_log = OperatorLog::create([
			'telecom_id' => 2,
			'message_id' => $params['messageid'],
			'status' => $params['status'],
			'destination' => $log_detail->recipient,
			'sender' => $log_detail->sms_log->sender_name,
			'operator_date' => $log_detail->send_at
		]);

		if ($operator_log) {
			if ($params['status'] == 'FAILD') {
				$status = 'Failed';
			} elseif ($params['status'] == 'REJECTD') {
				$status = 'Operator Rejected';
			} elseif ($params['status'] == 'EXPIRED') {
				$status = 'Expired';
			} else {
				$status = 'Delivered';
			}

			$log_detail->update([ 'status' => $status ]);
		}

		return response()->json(['status' => 'Received'], 200);
	}

	public function sendDexatel(Request $request)
	{
		$from = $request->from;
		$to = $request->to;
		$message = $request->message;

		$client = new Client();
		$response = $client->get('https://195.154.19.35/rest/send_sms?from=' . $from . '&to=' . $to . '&message=' . $message . '&username=93ILo4M7&password=n447I8P6',
			[
				'verify' => false
			]
		);

		$response = json_decode($response->getBody(), true);
		return response()->json($response);
	}

	public function sendNhn(Request $request)
	{
		$from = $request->from;
		$to = $request->to;
		$message = $request->message;

		$client = new Client();
		$response = $client->post('http://sms.tmtnetworks.com:8001/api',
			[
				'verify' => false,

				'form_params' => [
					'ani' => $from,
					'dnis' => $to,
					'username' => 'lfdev',
					'password' => 'K/eG#9vB',
					'message' => $message,
					'command' => 'submit',
					'longMessageMode' => 'payload'
				],
			]
		);

		$response = json_decode($response->getBody(), true);
		return $response;
	}
}
