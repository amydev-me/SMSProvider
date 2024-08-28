<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
	protected $fillable = ['sender_name', 'default'];

	public function sender_details()
	{
		return $this->hasMany(SenderDetail::class);
	}

	public function sender_users()
	{
		return $this->hasMany(SenderUser::class);
	}
}