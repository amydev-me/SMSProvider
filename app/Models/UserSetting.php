<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
	protected $fillable = ['user_id', 'newsletter_alert', 'credit_email_alert', 'credit_sms_alert', 'minimum_credit', 'sent'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}