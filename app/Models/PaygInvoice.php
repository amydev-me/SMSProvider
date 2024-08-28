<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaygInvoice extends Model
{
    protected $fillable = ['user_id', 'invoice_no', 'credit', 'total_credit', 'cost', 'payment_method', 'payment_date', 'invoice_date', 'status'];
    protected $dates = ['invoice_date','payment_date'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}