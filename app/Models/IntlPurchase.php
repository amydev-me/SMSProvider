<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IntlPurchase extends Model
{
	protected $fillable = ['amount', 'purchase_date', 'out_of_balance', 'obsolete'];
	protected $dates = ['purchase_date'];

	public function balances()
	{
		return $this->hasOne(IntlBalance::class);
	}

	// public function country_prices()
	// {
	// 	return $this->hasMany(CountryPrice::class);
	// }
}