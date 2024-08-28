<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
	protected $fillable = ['purchase_id', 'balance'];

	public function purchase()
	{
		return $this->belongsTo(Purchase::class);
	}
}