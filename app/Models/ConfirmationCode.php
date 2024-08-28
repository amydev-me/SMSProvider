<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmationCode extends Model
{
	protected $fillable = ['mobile', 'confirmation_code', 'count', 'expire_at'];
	protected $dates = ['expire_at'];
}
