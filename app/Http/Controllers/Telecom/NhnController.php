<?php

namespace App\Http\Controllers\Telecom;

use Lfuture\Nhn\NhnAction;

use App\Models\Purchase;
use App\Models\Balance;

class NhnController implements TelecomInterface
{
	protected $action;
	protected $operator_name;
	protected $message_parts;

	public function __construct($telecom, $operator_name, $message_parts, $country)
	{
		$this->action = new NhnAction($telecom->username, $telecom->secret, $telecom->end_point);

		$this->operator_name = $operator_name;
		$this->message_parts = $message_parts;

		$this->calculateBalance();
	}

	public function sendMessage($sender, $phone, $body, $long_message = FALSE)
	{
		return $this->action->send($sender, $phone, $body, $long_message);
	}

	public function calculateBalance()
	{
		try {
			$purchase = $this->getPurchase();
			$operator_price = $this->getOperatorPrice($this->operator_name, $purchase);

			$total_usage = $operator_price * $this->message_parts;

			if ( $purchase->balances->balance < $total_usage ) {
				$purchase->out_of_balance = '1';
				$purchase->save();

				$purchase = $this->getPurchase();
				$operator_price = $this->getOperatorPrice($this->operator_name, $purchase);
			}

			// $remaining_balance = $purchase->balances->balance - $total_usage;

			Balance::where('id', $purchase->balances->id)->decrement('balance', $total_usage);

		} catch (\Exception $e) {

		}
	}

	private function getPurchase()
	{
		return Purchase::with('balances')
						->where('out_of_balance', '0')
						->where('obsolete', '0')
						->first();
	}

	private function getOperatorPrice($operator_name, $purchase)
	{
		switch ($operator_name) {
			case 'Telenor':
				return $purchase->telenor_price;
				break;
			case 'Ooredoo':
				return $purchase->ooredoo_price;
				break;
			case 'MyTel':
				return $purchase->mytel_price;
				break;
			case 'MEC':
				return $purchase->mec_price;
				break;
			default:
				return $purchase->mpt_price;
				break;
		}
	}
}