<?php

namespace App\Models;

use App\Models\User;
use App\Uuids;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use Uuids;

    public $incrementing = false;

    protected $fillable = ['id', 'batch_id', 'user_id', 'admin_id', 'sender_name', 'message_content', 'message_parts', 'encoding', 'total_credit', 'total_sms', 'total_characters', 'source', 'type', 'sms_type', 'create_sms'];

    protected $dates = ['create_sms'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function log_details()
    {
        return $this->hasMany(LogDetail::class);
    }

    public function getDeliveredCount()
    {
        return $this->log_details()->where('status', 'Delivered')->count();
    }

    public function getFailedCount()
    {
        return $this->log_details()->where('status', 'Failed')->count();
    }

    public function getRejectedCount()
    {
        return $this->log_details()->where('status', 'Rejected')->count();
    }
}
