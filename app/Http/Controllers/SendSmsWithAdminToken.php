<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AdminToken;
use GuzzleHttp\Client;

trait SendSmsWithAdminToken
{
	protected function sendSmsWithAdminToken($to, $body)
	{
		try {
			$client = new Client();
			$token = AdminToken::first()->api_secret;

			$client->request(
				'POST', 'https://triplesms.com/api/send/message', [
					'headers' => [
						'Authorization' => "Bearer {$token}"
					],

					'json' => [
						'sender' => 'TripleSMS',
						'to' => $to,
						'body' => $body
					]
				]
			);
		} catch (RequestException $e) {
			throw $e;
		}
	}
}