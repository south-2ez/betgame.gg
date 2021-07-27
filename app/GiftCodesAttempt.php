<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftCodesAttempt extends Model
{
    use SoftDeletes;

    protected $table = 'gift_codes_attempts';
    protected $fillable = ['user_id', 'count'];

    protected $dates = ['deleted_at'];

    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeToday($query){
        $today = date('Y-m-d');
        return $query->whereDate('created_at',$today);
    }

}
