<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultEndpoint extends Model
{
	protected $fillable = ['gateway_id', 'telecom_id', 'sort_col', 'active_endpoint', 'inactive'];

	public $timestamps = false;

	protected $casts = [
		'active_endpoint' => 'boolean'
	];

	public function gateway()
	{
		return $this->belongsTo(Gateway::class);
	}

	public function telecom()
	{
		return $this->belongsTo(Telecom::class);
	}
}