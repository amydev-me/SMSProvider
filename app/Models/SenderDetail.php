<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SenderDetail extends Model
{
	protected $fillable = ['sender_id', 'operator_id', 'foreign', 'register_at'];

	public function sender()
	{
		return $this->belongsTo(Sender::class);
	}

	public function operator()
	{
		return $this->belongsTo(Operator::class);
	}
}