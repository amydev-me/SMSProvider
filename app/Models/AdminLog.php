<?php

namespace App\Models;


use App\Uuids;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use Uuids;


    public $incrementing = false;
    protected $fillable = ['id', 'batch_id','admin_id', 'message_content', 'message_parts', 'encoding', 'total_sms', 'total_characters', 'source'];


    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function admin_log_details()
    {
        return $this->hasMany(AdminLogDetail::class);
    }

    public function getDeliveredCount()
    {
        return $this->admin_log_details()->where('status', 'Delivered')->count();
    }

    public function getFailedCount()
    {
        return $this->admin_log_details()->where('status', 'Failed')->count();
    }

    public function getRejectedCount()
    {
        return $this->admin_log_details()->where('status', 'Rejected')->count();
    }
}
