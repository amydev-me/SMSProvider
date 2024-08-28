<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class AdminToken extends Model
{
    protected $fillable = ['admin_id', 'app_name', 'api_key','api_secret'];

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}