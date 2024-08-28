<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CountryPrice extends Model
{
	protected $fillable = ['country_id', 'intl_purchase_id', 'price'];

	public function country()
	{
		return $this->belongsTo(Country::class);
	}
}