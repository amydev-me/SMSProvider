<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRate extends Model
{
	protected $fillable = ['user_id', 'country_id', 'rate'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function country()
	{
		return $this->belongsTo(Country::class);
	}
}