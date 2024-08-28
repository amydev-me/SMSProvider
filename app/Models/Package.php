<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
	protected $fillable = ['packageName', 'total_sms', 'credit', 'cost', 'currency_type', 'active'];

	public function scopeGetFree($query)
	{
		return $query->where('packageName', 'Free')->first();
	}

	public function scopeNotFree($query)
	{
		return $query->where('packageName', '<>', 'Free');
	}

	public function user_packages()
	{
		return $this->hasMany(UserPackage::class);
	}

	public function order_notifications()
	{
		return $this->hasMany(OrderNotification::class);
	}

	public function promotions()
	{
		return $this->hasMany(Promotion::class);
	}
}
