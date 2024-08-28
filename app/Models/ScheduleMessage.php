<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleMessage extends Model
{
	protected $fillable = ['user_id', 'admin_id', 'sender_name', 'send_at', 'message_content', 'message_parts', 'encoding', 'total_credit', 'total_sms', 'total_characters', 'source', 'sms_type', 'status', 'warn_message', 'utc_timezone'];

	protected $dates = ['send_at'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function schedule_details()
	{
		return $this->hasMany(ScheduleDetail::class);
	}
}