<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
	protected $fillable = ['package_id', 'promo_credit', 'promo_status', 'max_purchase', 'active'];

	public function package()
	{
		return $this->belongsTo(Package::class);
	}

	public function scopeCurrent($query)
	{
		return $query::where('status', '1')->first();
	}
}