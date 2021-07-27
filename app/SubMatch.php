<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubMatch extends Match
{
    protected $table = 'matches';
    
    public function mainMatch() {
        return $this->belongsTo('App\Match', 'main_match');
    }
    
    public function bets()
    {
        return $this->hasMany('App\Bet', 'match_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('type', 'sub');
        });
    }
}
