<?php

namespace App\Models;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;

class LogDetail extends Model
{

    use Uuids;
    public $incrementing = false;
    protected $fillable = ['sms_log_id', 'message_id', 'recipient', 'country_id', 'operator_id', 'operator', 'status', 'source', 'total_usage', 'send_at'];
    protected $dates = ['send_at'];

    public function sms_log()
    {
        return $this->belongsTo(SmsLog::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
