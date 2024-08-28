<?php

namespace Lfuture\Nhn;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class NhnAction
{
	protected $username;
	protected $secret;
	protected $endpoint;

	public function __construct($username, $secret, $endpoint)
	{
		$this->username = $username;
		$this->secret = $secret;
		$this->endpoint = $endpoint;

		$this->client = new Client();
	}

	public function send($from, $to, $body, $long_message)
	{
		try {
			$form_params = [
				'ani' => $from,
				'dnis' => $to,
				'username' => $this->username,
				'password' => $this->secret,
				'message' => $body,
				'command' => 'submit'
			];

			if ( $long_message == TRUE ) {
				$form_params['longMessageMode'] = 'payload';
			}

			$response = $this->client->post($this->endpoint,
				[
					'verify' => FALSE,

					'form_params' => $form_params,
				]
			);

			$response = json_decode($response->getBody(), TRUE);

			return $response;

		} catch (RequestException  $e) {
			throw $e;
		}
	}
}