<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifiedBy extends Model
{
    use SoftDeletes;
    
    protected $table = 'users_verified';
    protected $fillable = ['user_id', 'verified_type', 'verified_by', 'verified_by_user_id'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function partner(){
        return $this->belongsTo('App\Partner', 'verified_by');
    }
}
