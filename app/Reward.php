<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = ['user_id', 'type', 'class_id', 'description', 'credits', 'added_by'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function getRewardSourceAttribute()
    {
        switch($this->attributes['type']) {
            case 'referral':
                break;
            case 'badge':
                break;
            case 'fb_page_like':
                break;
        }
    }
    
    public function addedBy()
    {
        return $this->belongsTo('App\User', 'added_by');
    }
}
