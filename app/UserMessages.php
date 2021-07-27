<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMessages extends Model
{
    use SoftDeletes;
    
    protected $table = 'user_messages';
    protected $fillable = ['from_user_id', 'user_id', 'message', 'status'];
    protected $dates = ['deleted_at'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function sentBy(){
        return $this->belongsTo('App\User', 'from_user_id');
    }

    public function scopeUnread($query){
        return $query->where('status',0);
    }
}
