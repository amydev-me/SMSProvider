<?php

namespace App\Http\Controllers\Telecom;

use Lfuture\Dexatel\DexatelAction;

use App\Models\IntlPurchase;
use App\Models\IntlBalance;

class DexatelController implements TelecomInterface
{
	protected $action;
	protected $country;
	protected $message_parts;

	public function __construct($telecom, $operator_name, $message_parts, $country)
	{
		$this->action = new DexatelAction($telecom->username, $telecom->secret, $telecom->end_point);

		$this->country = $country;
		$this->message_parts = $message_parts;

		$this->calculateBalance();
	}

	public function sendMessage($sender, $phone, $body, $long_message = FALSE)
	{
		return $this->action->send($sender, $phone, $body);
	}

	public function calculateBalance()
	{
		try {
			$purchase = $this->getPurchase();

			$total_usage = $this->country->cost * $this->message_parts;

			if ( $purchase->balances->balance < $total_usage ) {
				$purchase->out_of_balance = '1';
				$purchase->save();

				$purchase = $this->getPurchase();
			}

			// $remaining_balance = $purchase->balances->balance - $total_usage;

			IntlBalance::where('id', $purchase->balances->id)->decrement('balance', $total_usage);

		} catch (\Exception $e) {

		}
	}

	private function getPurchase()
	{
		return IntlPurchase::with('balances')
							->where('out_of_balance', '0')
							->where('obsolete', '0')
							->first();
	}
}