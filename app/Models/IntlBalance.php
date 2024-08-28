<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IntlBalance extends Model
{
	protected $fillable = ['intl_purchase_id', 'balance'];

	public function intl_purchase()
	{
		return $this->belongsTo(IntlPurchase::class);
	}
}