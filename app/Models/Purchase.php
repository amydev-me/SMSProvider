<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	protected $fillable = ['amount', 'purchase_date', 'mpt_price', 'telenor_price', 'ooredoo_price', 'mytel_price', 'mec_price', 'out_of_balance', 'obsolete'];
	protected $dates = ['purchase_date'];

	public function balances()
	{
		return $this->hasOne(Balance::class);
	}

	public function scopeActiveBalance($query)
	{
		return $query->where('out_of_balance', '0');
	}
}