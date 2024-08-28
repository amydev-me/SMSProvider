<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    protected $fillable = ['user_id', 'package_id', 'promotion_id', 'total_sms', 'credit', 'total_usd', 'extra_credit', 'total_credit', 'cost', 'payment_method', 'payment_date', 'order_date', 'status', 'invoice_no'];
    protected $dates = ['order_date','payment_date'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function scopeFindUserPackage($query, $id)
    {
        return $query->where('id', $id);
    }
}
