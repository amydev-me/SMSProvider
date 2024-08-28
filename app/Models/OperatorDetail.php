<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorDetail extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'country_id', 'operator_id', 'starting_number'
	];
}
