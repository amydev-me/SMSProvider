<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'questions', 'answers', 'publish_date','inactive'];

    protected $casts = [
        'inactive' => 'boolean'
    ];

    protected $dates = [
        'publish_date', 'created_at', 'updated_at'
    ];
}