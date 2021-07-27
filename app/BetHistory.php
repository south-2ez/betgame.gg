<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetHistory extends Model
{
    protected $fillable = ['type', 'match_id', 'bet_id', 'amount', 'user_id', 'curr_credits'];
    
    public function bet()
    {
        return $this->belongsTo('App\Bet');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
