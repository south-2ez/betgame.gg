<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $hidden = [
        'bets'
    ];
    
    public function bets()
    {
        return $this->hasMany('App\Bet');
    }

    public function getTournamentWinPercentageAttribute()
    {
        $total_bets = \App\Bet::tournament()->sum('amount');
        $team_bets = $this->bets()->tournament()->sum('amount');
        
        return $total_bets ? ($team_bets / $total_bets) * 100 : 0;
    }
    
    public function getTournamentRatioAttribute()
    {
        $total_bets = \App\Bet::tournament()->sum('amount');
        $team_bets = $this->bets()->tournament()->sum('amount');
        
        return $team_bets ? $total_bets / $team_bets * (1 - 0.1) : 0;
    }
    
    public function getTournamentWinningAmountAttribute()
    {
        return 100 * $this->tournamentRatio;
    }
    
    public function potentialTournamentWinningPerUser($user_id)
    {
        $user_bet = \App\User::find($user_id)->getTournamentBetPerTeam($this->id);
        $team_bets = $this->bets()->tournament()->sum('amount');
        $total_team_payout = ($team_bets * $this->tournamentRatio);
        return $team_bets ? ($user_bet / $team_bets) * $total_team_payout : 0;
    }
    
    public function potentialMatchWinningPerUser($match_id, $user_id, $opt_amount = 0)
    {
        $user = \App\User::find($user_id);
        $user_bet = hasMatchManagementAccess($user) ? $user->getMatchBetAmount($match_id, $opt_amount, $this->id) :
            $user->getMatchBetAmount($match_id, $opt_amount);
        $team_bets = $this->bets->where('match_id', $match_id)->sum('amount') + $opt_amount;
        $total_team_payout = ($team_bets * $this->matchRatio($match_id, $opt_amount));
        return $team_bets ? ($user_bet / $team_bets) * $total_team_payout : 0;
    }
    
    public function matchWinPercentage($match_id)
    {
        $bet_count = \App\Bet::where('match_id', $match_id)->count();
        $total_bets = \App\Bet::where('match_id', $match_id)->sum('amount');
        $team_bets = \App\Bet::where('match_id', $match_id)->where('team_id', $this->id)->sum('amount');
        
        if($bet_count)
            return $total_bets ? ($team_bets / $total_bets) * 100 : 0;
        else
            return 50;
    }
    
    public function matchRatio($match_id, $opt_amount = 0)
    {
        $bet_count = \App\Bet::where('match_id', $match_id)->count();
        $match_fee = \App\Match::find($match_id)->fee;
        $total_bets = \App\Bet::where('match_id', $match_id)->sum('amount') + $opt_amount;
        $team_bets = $this->bets->where('match_id', $match_id)->sum('amount') + $opt_amount;
        
        if($bet_count)
            return $team_bets ? $total_bets / $team_bets * (1 - $match_fee) : 0;
        else
            return 2;
    }
    
    public function matchAdminRatio($match_id, $opt_amount = 0, $bet_id = null)
    {
        $match_fee = \App\Match::find($match_id)->fee;
        if ($bet_id) {
            $bet_count = \App\Bet::where('match_id', $match_id)->where('id', '!=', $bet_id)->count();
            $total_bets = \App\Bet::where('match_id', $match_id)->where('id', '!=', $bet_id)->sum('amount') + $opt_amount;
            $team_bets = $this->bets->where('match_id', $match_id)->where('id', '!=', $bet_id)->sum('amount') + $opt_amount;
        } else {
            $bet_count = \App\Bet::where('match_id', $match_id)->count();
            $total_bets = \App\Bet::where('match_id', $match_id)->sum('amount') + $opt_amount;
            $team_bets = $this->bets->where('match_id', $match_id)->sum('amount') + $opt_amount;
        }

        if($bet_count)
            return $team_bets ? $total_bets / $team_bets * (1 - $match_fee) : 0;
        else
            return 2;
    }
    
    public function potentialMatchWinningPerMatchManager($match_id, $user_id, $opt_amount = 0, $bet_id = 0)
    {
        $user = \App\User::find($user_id);
        $user_bet = $opt_amount;
        $team_bets = $this->bets->where('match_id', $match_id)->where('id', '!=', $bet_id)->sum('amount') + $opt_amount;
        $total_team_payout = ($team_bets * $this->matchAdminRatio($match_id, $opt_amount, $bet_id));
        return $team_bets ? ($user_bet / $team_bets) * $total_team_payout : 0;
    }
    
    public function matchA()
    {
        return $this->hasMany('App\Match', 'team_a');
    }
    
    public function matchB()
    {
        return $this->hasMany('App\Match', 'team_b');
    }
    
    public function matches()
    {
        return $this->matchA->merge($this->matchB);
    }
    
    public function leagues()
    {
        return $this->belongsToMany('App\League', 'league_team');
    }
    
    public function scopeType($query, $type) {
        return $query->where('type', $type);
    }
}
