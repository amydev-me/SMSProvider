<?php

namespace App\Models;

use App\Notifications\ResetPasswordNoti;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Auth;

class User extends Authenticatable
{
	use Notifiable, HasApiTokens, CanResetPassword;

	protected $guarded = 'web';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'username', 'email', 'mobile', 'password', 'full_name', 'company', 'account_type', 'address', 'accept_terms', 'accept_updated_terms', 'newsletter', 'sms_type', 'usd_rate', 'minimum_sms', 'minimum_credit', 'verified', 'obsolete', 'new_email', 'new_email_token'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token', 'new_email', 'new_email_token'
	];

	protected $casts = [
		'is_admin' => 'boolean'
	];

	public function isAdmin()
	{
		return false;
	}

	public function user_tokens()
	{
		return $this->hasMany(UserToken::class);
	}

	public function verify_user_email()
	{
		return $this->hasOne(VerifyUserEmail::class);
	}

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPasswordNoti($token));
	}

	public function user_packages()
	{
		return $this->hasMany(UserPackage::class);
	}

	public function payg_invoices()
	{
		return $this->hasMany(PaygInvoice::class);
	}

	public function sms_logs()
	{
		return $this->hasMany(SmsLog::class);
	}

	public function user_balance()
	{
		return $this->hasOne(UserBalance::class);
	}

	public function credits()
	{
		$user_id = Auth::guard('web')->user()->id;

		$balance = UserBalance::where('user_id', $user_id)->first();

		if ($balance) {
			return $balance->balance;
		}

		$balance = $this->user_packages()->where('status', 'paid')->sum('total_credit') - $this->usage();

		$new_balance = UserBalance::create(['user_id' => $user_id, 'balance' => $balance]);

		return UserBalance::where('user_id', $user_id)->value('balance');
	}

	public function usage($year = NULL)
	{
		$usage = SmsLog::where('user_id', Auth::guard('web')->user()->id);

		if ($year != NULL) {
			$usage = $usage->whereYear('created_at', $year);
		}

		return $usage->sum('total_credit');
	}

	public function unpaid_credits()
	{
		return $this->usage() - $this->payg_invoices()->where('status', 'paid')->sum('total_credit');
	}

	public function scopeCurrentUser($query)
	{
		return $query->where('id', Auth::guard('web')->user()->id);

	}

	public function schedule_messages()
	{
		return $this->hasMany(ScheduleMessage::class);
	}


	public function contacts()
	{
		return $this->hasMany(Contact::class);
	}

	public function groups()
	{
		return $this->hasMany(Group::class);
	}


	public function getSmsSentCount($year)
	{
		$user_id = Auth::guard('web')->user()->id;

		return LogDetail::whereHas('sms_log', function ($q) use ($user_id) {
							$q->where('user_id', $user_id);
						})
						->whereYear('created_at', $year)
						->where('status', 'Delivered')
						->count();
	}

	public function getDeliveryRate($year)
	{
		$user_id = Auth::guard('web')->user()->id;

		$query = LogDetail::whereHas('sms_log', function ($q) use ($user_id) {
								$q->where('user_id', $user_id);
							})
							->whereYear('created_at', $year);

		$all_count = $query->count();
		$deliver_count = $query->where('status', 'Delivered')->count();
		
		if ($all_count != 0) {
			return round(($deliver_count / $all_count) * 100);
		}
	}

	public function user_setting()
	{
		return $this->hasOne(UserSetting::class);
	}

	public function user_senders()
	{
		return $this->hasMany(UserSender::class);
	}
}


// public function usd_credits()
// {
// 	return $this->user_packages()->where('status', 'paid')->sum('total_usd') - $this->usd_usage();
// }

// public function usd_usage()
// {
// 	$user_id = Auth::guard('web')->user()->id;
// 	$logdetails = SmsLog::where('user_id', $user_id)->get();
// 	return $logdetails->sum('total_sms') * Auth::guard('web')->user()->usd_rate;
// }

//	public function findForPassport($identifier)
//	{
//		return $this->user_tokens()->Where('api_key', $identifier)->orWhere('username', $identifier)->first();
//	}
//
//	public function validateForPassportPasswordGrant($password)
//	{
//		$user = UserToken::where('api_secret', $password)->first();
//
//		return $user ? true : false;
//	}
