<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $fillable = ['iso', 'code', 'name', 'prefix', 'rate', 'cost', 'status'];

	public function operators()
	{
		return $this->hasMany(Operator::class);
	}
}