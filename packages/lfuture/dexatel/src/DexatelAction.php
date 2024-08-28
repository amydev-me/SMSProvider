<?php

namespace Lfuture\Dexatel;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class DexatelAction
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

	public function send($from, $to, $body)
	{
		try {
			$response = $this->client->get($this->endpoint . '?from=' . $from . '&to=' . $to . '&message=' . $body . '&username=' . $this->username . '&password=' . $this->secret,
				[
					'verify' => FALSE
				]
			);

			$response = json_decode($response->getBody(), TRUE);

			return $response;

		} catch (RequestException $e) {
			throw $e;
		}
	}
}