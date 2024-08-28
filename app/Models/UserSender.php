<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSender extends Model
{
	protected $fillable = ['user_id', 'sender_name', 'operator_id', 'foreign', 'register_at'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function operator()
	{
		return $this->belongsTo(Operator::class);
	}
}