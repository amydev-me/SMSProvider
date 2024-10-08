<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
	protected $fillable = ['text', 'expire_at'];

	protected $dates = [
		'expire_at'
	];
}