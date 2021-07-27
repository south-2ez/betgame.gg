<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchReport extends Model
{
    protected $fillable = [
        'id',
        'settled_by',
        'data',
        'total_match_bet',
        'match_fee',
        'total_fees_collected',
        'total_payout',
        'total_bettors',
        'average_match_bet',
        'name',
        'league_id',
        'status',
        'circulating_credits_before_settled',
        'circulating_credits_after_settled',
        'label',
        'round_off_earnings',
    ];
    
    /**
     * Get the match league
     */
    public function league()
    {
        return $this->belongsTo('App\League');
    }

    /**
     * Get the match report data
     *
     * @return array
     */
    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }

    /**
     * Get the user who settled the match
     *
     * @return User
     */
    public function settledBy()
    {
        return $this->belongsTo('App\User','settled_by');
    }


}
