<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SenderUser extends Model
{
	protected $fillable = ['sender_id', 'user_id'];

	public function sender()
	{
		return $this->belongsTo(Sender::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}