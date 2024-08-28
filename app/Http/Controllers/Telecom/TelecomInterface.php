<?php

namespace App\Http\Controllers\Telecom;

interface TelecomInterface
{
	public function sendMessage($sender, $phone, $body, $long_message = FALSE);

	public function calculateBalance();
}