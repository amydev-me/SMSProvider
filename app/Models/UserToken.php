<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $fillable = ['user_id', 'app_name', 'api_key','api_secret'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}