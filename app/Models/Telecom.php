<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telecom extends Model
{
	protected $fillable = ['name', 'description', 'username', 'secret', 'end_point', 'inactive'];

	protected $casts = [
		'inactive' => 'boolean'
	];

	public function default_endpoints()
	{
		return $this->hasMany(DefaultEndpoint::class);
	}
}