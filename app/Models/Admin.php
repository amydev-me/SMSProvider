<?php

namespace App\Models;

use App\Models\AdminToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Auth;

class Admin extends Authenticatable
{
	use HasApiTokens;

	protected $guard = 'admin';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'username', 'password', 'full_name', 'role', 'obsolete', 'last_login'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function admin_tokens()
	{
		return $this->hasMany(AdminToken::class);
	}

	public function getBalance()
	{
		return Balance::whereHas('purchase', function ($query) {
							$query->where('obsolete', 0)
									->where('out_of_balance', 0);
						})
						->sum('balance');
	}

	public function getIntlBalance()
	{
		return IntlBalance::whereHas('intl_purchase', function ($query) {
								$query->where('obsolete', 0)
										->where('out_of_balance', 0);
							})
							->sum('balance');
	}

	public function scopeCurrentAdmin($query)
	{
		return $query->where('id', Auth::guard('admin')->user()->id);
	}
}
