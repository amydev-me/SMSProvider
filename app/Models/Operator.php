<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'country_id', 'name', 'status'
	];

	public function country()
	{
		return $this->belongsTo(Country::class);
	}

	public function operator_detail()
	{
		return $this->hasMany(OperatorDetail::class);
	}
}
