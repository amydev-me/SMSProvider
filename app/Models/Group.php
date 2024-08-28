<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    protected $fillable=['user_id','groupName','description'];
    protected $appends=['contact_count'];
    protected $hidden = ['pivot'];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function contacts(){
        return $this->belongsToMany(Contact::class);
    }

    public function getContactCountAttribute(){
    		return $this->contacts()->count();
    }

    public function scopeCurrentUser($query){
        return $query->where('user_id',Auth::guard('web')->user()->id);
    }

    public function scopeFindById($query, $id)
    {
        return $query->where('id', $id);
    }


}