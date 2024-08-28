<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyUserEmail extends Model
{
    protected $fillable=['user_id','token','expire_at','resend_count','resent_in','resend_at'];

    protected $dates=['expire_at','resend_at'];

    public $timestamps=false;

    public function user(){
        $this->belongsTo(User::class);
    }
}
