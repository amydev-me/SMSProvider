<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleDetail extends Model
{
    protected $fillable = ['schedule_message_id', 'recipient', 'country', 'operator', 'source', 'total_usage'];

    public function schedule_message(){
        return $this->belongsTo(ScheduleMessage::class);
    }
}
