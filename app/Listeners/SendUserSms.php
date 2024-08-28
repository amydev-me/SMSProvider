<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\AdminToken;
use GuzzleHttp\Client;

class SendUserSms
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserRegistered $event
	 * @return void
	 */
	public function handle(UserRegistered $event)
	{
		$user = $event->user;

		try {
			$token = AdminToken::first()->api_secret;
			$client = new Client();

			$client->request(
				'POST', 'https://triplesms.com/api/send/message', [
					'headers' => [
						'Authorization' => "Bearer {$token}"
					],

					'json' => [
						'sender' => 'TripleSMS',
						'to' => $user->mobile,
						'body' => 'Thank you for using TripleSMS. We offer various pricing that suit your business. If your requirements are not met, feel free to contact us.'
					]
				]
			);
		} catch (RequestException $e) {
			throw $e;
		}
	}
}