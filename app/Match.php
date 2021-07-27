<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\LogUserCredits;

class Match extends Model
{
    protected $fillable = [ 
        'type',
        'game_grp',
        'sub_type',
        'is_over_under',
        'name', 
        'league_id', 
        'best_of', 
        'schedule', 
        'team_a',
        'team_a_ratio', 
        'team_b',
        'team_b_ratio', 
        'team_c',
        'team_c_ratio',         
        'team_winner',
        'fee', 
        're_opened',
        'status', 
        'teama_score',
        'teamb_score',
        'is_initial_odds_enabled',
        'team_a_initial_odd',
        'team_b_initial_odd',
        'team_c_initial_odd',
        'team_a_total_bets',
        'team_b_total_bets',
        'team_c_total_bets',
        'team_a_threshold_percent',
        'team_a_max_threshold_percent',
        'team_b_threshold_percent',
        'team_b_max_threshold_percent', 
        'team_c_threshold_percent',
        'team_c_max_threshold_percent',                
        'label',
        'stream_twitch',
        'stream_yt',
        'stream_fb',
        'stream_other',
        'more_info_link',
        'main_match',
        'date_settled',
        'date_set_live',
        'date_reopened',
    ];

    protected $dates = ['schedule'];

    protected $hidden = [
        'is_over_under',
        're_opened',
        'is_initial_odds_enabled',
        'team_a_initial_odd',
        'team_b_initial_odd',      
        'team_a_total_bets', 
        'team_b_total_bets',
        'team_a_threshold_percent',
        'team_a_max_threshold_percent',
        'team_b_threshold_percent',
        'team_b_max_threshold_percent',
        'date_settled',
        'date_set_live',
        'date_reopened',
    ];
    
    public function cacheKey()
    {
        return sprintf(
            "%s/%s-%s",
            $this->getTable(),
            $this->getKey(),
            $this->updated_at->timestamp
        );
    }

    public function league()
    {
        return $this->belongsTo('App\League');
    }
    
    public function teamwinner()
    {
        return $this->belongsTo('App\Team', 'team_winner');
    }
    
    public function teamA()
    {
        return $this->belongsTo('App\Team', 'team_a');
    }
    
    public function teamB()
    {
        return $this->belongsTo('App\Team', 'team_b');
    }

    public function teamC()
    {
        return $this->belongsTo('App\Team', 'team_c');
    }    
    
    public function getNameAttribute()
    {
        return $this->attributes['name'] ? $this->attributes['name'] :
            ($this->teamA ? $this->teamA->name : '') . ' vs ' . 
                ($this->teamB ? $this->teamB->name . ' ' . $this->bestOf : '');
    }
    
    public function bets()
    {
        return $this->hasMany('App\Bet');
    }
    
    public function getTotalBetAmountAttribute()
    {
        return $this->bets->sum('amount');
    }
    
    public function bettors(){
  
        return $this->belongsToMany('App\User','bets','match_id','user_id')->withPivot('amount','id','ratio','team_id');
    }

    public function getBestOfAttribute()
    {
        return 'BO' . $this->attributes['best_of'];
    }
    
    public function isClosing($min = 5)
    {
        return \Carbon\Carbon::now()->diffInSeconds($this->schedule, false) <= ($min * 60);
    }
    
    public function scopeSchedNameSort($query) {
        return $query->orderBy('matches.schedule', 'desc')->orderBy('name');
    }
    
    public function scopeOpenMatches($query) {
        return $query->where('matches.status', 'open');
    }
    
    public function scopeLiveMatches($query) {
        return $query->where('matches.status', 'open');
    }
    
    public function scopeActiveMatches($query) {
        return $query->whereIn('matches.status', ['open', 'ongoing']);
    }
    
    
    public function scopeMainMatches($query) {
        return $query->where('matches.type', 'main');
    }

    public function scopeDota2Matches($query) {
        return $query->join('leagues', 'leagues.id', '=', 'matches.league_id')
                ->where('leagues.type', 'dota2')->select('matches.*');
    }

    public function scopeCsgoMatches($query) {
        return $query->join('leagues', 'leagues.id', '=', 'matches.league_id')
                ->where('leagues.type', 'csgo')->select('matches.*');
    }

    public function scopeLolMatches($query) {
        return $query->join('leagues', 'leagues.id', '=', 'matches.league_id')
                ->whereIn('leagues.type', ['lol','LoL'])->select('matches.*');
    }

    public function scopeNbaPlayoffsMatches($query) {
        return $query->join('leagues', 'leagues.id', '=', 'matches.league_id')
                ->where('leagues.type', 'nbaplayoffs')->select('matches.*');
    }    

    public function scopeSportsMatches($query) {
        return $query->join('leagues', 'leagues.id', '=', 'matches.league_id')
                ->whereNotIn('leagues.type', ['csgo', 'dota2', 'lol', 'LoL', 'nbaplayoffs'])->select('matches.*');
    }

    public function scopeNotNbaPlayoffsMatches($query) {
        return $query->join('leagues', 'leagues.id', '=', 'matches.league_id')
                ->whereNotIn('leagues.type', ['nbaplayoffs'])->select('matches.*');
    }    
    
    
    public function subMatches() {
        return $this->hasMany('App\SubMatch', 'main_match');
    }

    public function isMatchInitialOddsEnable($query){
        return $query->where('matches.is_initial_odds_enabled', 1);
    }

    public static function boot()
    {
        parent::boot();
        
        static::updating(function($match) {

            // dd($match->getDirty());
            if($match->isDirty()) {

                \DB::table('bets')
                    ->where(
                        [
                            'match_id' => $match->id,
                            'team_id' => $match->getOriginal('team_a')
                        ])
                    ->update([
                        'team_id' => $match->team_a
                    ]);      
                    
                \DB::table('bets')
                    ->where(
                        [
                            'match_id' => $match->id,
                            'team_id' => $match->getOriginal('team_b')
                        ])
                    ->update([
                        'team_id' => $match->team_b
                    ]);                       

                // foreach($match->bets as $bet) {

                //     if($bet->team_id == $match->getOriginal('team_a')) {
                //         $bet->team_id = $match->team_a;
                //         $bet->save();
                //     }
                //     if($bet->team_id == $match->getOriginal('team_b')) {
                //         $bet->team_id = $match->team_b;
                //         $bet->save();
                //     }
                // }
                
                if ($match->type == 'main') {
                    foreach ($match->subMatches as $subMatch) {
                        if ($subMatch->game_grp > 1)
                            $subMatch->schedule = $match->schedule->addHours($subMatch->game_grp - 1);
                        else {
                            $subMatch->schedule = $match->schedule;
                            $subMatch->fee = $match->fee;
                        }
                        $subMatch->save();
                    }
                } else {
                    if($match->sub_type == 'main' && $match->game_grp > 1) {
                        $subMatches = \App\SubMatch::where('main_match', $match->main_match)
                                ->where('game_grp', $match->game_grp)
                                ->where('id', '!=', $match->id)->get();
                        foreach ($subMatches as $subMatch) {
                            $subMatch->schedule = $match->schedule;
                            $subMatch->fee = $match->fee;
                            $subMatch->save();
                        }
                    }
                }

                /**this script will log the user credits */
                //if ($match->type == 'main') {
                    if (in_array(request('team_winner'), ['draw', 'cancelled'])) {
                        //set as draw
                        if( ($match->getOriginal('status') <> 'draw' && $match->status == 'draw') || $match->getOriginal('status') <> 'cancelled' && $match->status == 'cancelled' ) {
                            $draw_bet_logs = DB::table('bets')
                                ->select(
                                    'user_id',
                                    DB::raw("{$match->id} as reference_id"),
                                    DB::raw("'Match' as model"),
                                    DB::raw("'DRAW_MATCH' as action"),
                                    DB::raw("amount as amount"),
                                    DB::raw("(credits + amount) as new_credit"),
                                    DB::raw("credits as current_credit"),
                                    DB::raw("now() as created_at"),
                                    DB::raw("now() as updated_at")
                                )
                                ->join('users','users.id','=','bets.user_id')
                                ->where(['match_id' => $match->id])
                                ->get();

                            if($draw_bet_logs) {
                                // $job = (new LogUserCredits(json_decode( json_encode($draw_bet_logs), true)))
                                //     ->onConnection('redis')
                                //     ->onQueue('low')
                                //     ->delay(Carbon::now()->addMinutes(2));

                                // dispatch($job);
                                dispatch(new LogUserCredits(json_decode( json_encode($draw_bet_logs), true)));
                            }
                        }
                    } else {
                        //ongoing to settled
                        if($match->getOriginal('status') == 'ongoing' && $match->status == 'settled') {
                            $winner_bet_logs = DB::table('bets')
                                ->select(
                                    'user_id',
                                    DB::raw("{$match->id} as reference_id"),
                                    DB::raw("'Match' as model"),
                                    DB::raw("'SETTLED_MATCH' as action"),
                                    DB::raw("amount * ratio as amount"),
                                    DB::raw("(credits + (amount * ratio)) as new_credit"),
                                    'credits as current_credit',
                                    DB::raw("now() as created_at"),
                                    DB::raw("now() as updated_at")
                                )
                                ->join('users','users.id','=','bets.user_id')
                                ->where(
                                    [
                                        'match_id' => $match->id,
                                        'team_id'  => request('team_winner')
                                    ])
                                ->get();

                            if($winner_bet_logs) {
                                // $job = (new LogUserCredits(json_decode( json_encode($winner_bet_logs), true)))
                                //     ->onConnection('redis')
                                //     ->onQueue('low')
                                //     ->delay(Carbon::now()->addMinutes(2));

                                // dispatch($job);
                                dispatch(new LogUserCredits(json_decode( json_encode($winner_bet_logs), true)));
                            }

                            $loser_bet_logs = DB::table('bets')
                                ->select(
                                    'user_id',
                                    DB::raw("{$match->id} as reference_id"),
                                    DB::raw("'Match' as model"),
                                    DB::raw("'SETTLED_MATCH' as action"),
                                    'amount',
                                    DB::raw("(credits - amount) as new_credit"),
                                    'credits as current_credit',
                                    DB::raw("now() as created_at"),
                                    DB::raw("now() as updated_at")
                                )
                                ->join('users','users.id','=','bets.user_id')
                                ->where(
                                    [
                                        ['match_id','=',$match->id],
                                        ['team_id', '!=', request('team_winner') ]
                                    ])
                                ->get();

                            if($loser_bet_logs) {
                                // $job = (new LogUserCredits(json_decode( json_encode($loser_bet_logs), true)))
                                //     ->onConnection('redis')
                                //     ->onQueue('low')
                                //     ->delay(Carbon::now()->addMinutes(2));

                                // dispatch($job);
                                dispatch(new LogUserCredits(json_decode( json_encode($loser_bet_logs), true)));
                            }
                        }

                        //settled to ongoing/live
                        if($match->getOriginal('status') == 'settled' && $match->status == 'ongoing') {
                            //revert winner
                            $revert_winner_bet_logs = DB::table('bets')
                                ->select(
                                    'user_id',
                                    DB::raw("{$match->id} as reference_id"),
                                    DB::raw("'Match' as model"),
                                    DB::raw("'REVERT_MATCH' as action"),
                                    DB::raw("(amount * ratio) as amount"),
                                    DB::raw("credits as new_credit"),
                                    DB::raw("(credits + (amount * ratio)) as current_credit"),
                                    DB::raw("now() as created_at"),
                                    DB::raw("now() as updated_at")
                                )
                                ->join('users','users.id','=','bets.user_id')
                                ->where(
                                    [
                                        'match_id' => $match->id,
                                        'team_id'  => $match->getOriginal('team_winner')
                                    ])
                                ->get();

                            if($revert_winner_bet_logs) {
                                // $job = (new LogUserCredits(json_decode( json_encode($revert_winner_bet_logs), true)))
                                //     ->onConnection('redis')
                                //     ->onQueue('low')
                                //     ->delay(Carbon::now()->addMinutes(2));

                                // dispatch($job);
                                dispatch(new LogUserCredits(json_decode( json_encode($revert_winner_bet_logs), true)));
                            }
                        }
                    }
                //}
                /** end log user credits */

            }
        });
        
        // cascade delete related submatches
        static::deleted(function($match) {
            foreach($match->subMatches as $subMatch) {
                $subMatch->delete();
            }
        });
    }
}
