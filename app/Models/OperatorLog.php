<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorLog extends Model
{
	protected $fillable = ['telecom_id', 'message_id', 'status', 'destination', 'sender', 'operator_date'];
}