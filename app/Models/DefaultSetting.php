<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSetting extends Model
{
    protected $fillable=['sender','facebook_url','twitter_url','linkedin_url','phones','email'];

    public $timestamps = false;
}
