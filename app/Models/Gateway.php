<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $fillable=['country_id','operator_id','encoding','sender','inactive'];

    public $timestamps=false;

    protected $casts = [
        'inactive' => 'boolean'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function operator(){
        return $this->belongsTo(Operator::class);
    }
}
