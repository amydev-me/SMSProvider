<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderNotification extends Model
{
	protected $fillable = ['user_package_id', 'package_id', 'message', 'read'];
	protected $dates = ['read_at'];

	protected $appends = ['package_name'];

	public function package()
	{
		return $this->belongsTo(Package::class);
	}

	public function getPackageNameAttribute()
	{
		return Package::where('id', $this->package_id)->first()->packageName;
	}
}
