<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = ['name', 'type', 'description', 'image', 'league_winner'];

    protected $hidden = [
        'circulating_credits_after_settled',
        'circulating_credits_before_settled',
    ];
    
    public function matches()
    {
        return $this->hasMany('App\Match');
    }
    
    public function teams()
    {
        return $this->belongsToMany('App\Team', 'league_team')->withPivot('is_favorite');
    }
    
    public function bets()
    {
        return $this->hasMany('App\Bet');
    }
    
    public function tournamentBets()
    {
        return $this->hasMany('App\Bet')->where('type', 'tournament');
    }
    
    public function getTournamentRatioAttribute()
    {
        $total_bets = $this->tournamentBets->sum('amount');
        $team_bets = $this->bets()->tournament()->sum('amount');
        
        return $team_bets ? $total_bets / $team_bets * (1 - 0.1) : 0;
    }
    
    public function champion()
    {
        return $this->hasOne('App\Team', 'id', 'league_winner');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopeOngoing($query)
    {
        return $query->where('expired', 0);
    }
    
    public function scopePast($query)
    {
        return $query->where('status', 0);
    }
}
