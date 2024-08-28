<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    protected $hidden=['pivot'];

    protected $fillable = ['user_id', 'contactName', 'email', 'mobile', 'work', 'companyName', 'address', 'birthdate'];

    protected $dates=['birthdate'];




    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function scopeCurrentUser($query)
    {
        return $query->where('user_id', Auth::guard('web')->user()->id);
    }

    public function scopeFindById($query, $id)
    {
        return $query->where('id', $id);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}