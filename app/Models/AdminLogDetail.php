<?php

namespace App\Models;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;

class AdminLogDetail extends Model
{
    use Uuids;
    public $incrementing = false;
    protected $fillable = ['admin_log_id', 'message_id', 'recipient', 'country', 'operator', 'status', 'source', 'total_usage'];
}
