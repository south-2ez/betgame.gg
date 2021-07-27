<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\LogUserCredits;
use Carbon\Carbon;

class Bet extends Model
{
    protected $appends = ['tournament'];
    protected $guarded=[];
            
    public function team()
    {
        return $this->belongsTo('App\Team');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function scopeTournament($query, $league_id = 1)
    {
        return $query->where('type', 'tournament')->where('league_id', $league_id);
    }
    
    public function scopeMatches($query)
    {
        return $query->where('type', 'matches');
    }
    
    public function getTournamentAttribute()
    {
        return 'The International 2017';
    }
    
    public function match()
    {
        return $this->belongsTo('App\Match');
    }
    
    public function league()
    {
        return $this->belongsTo('App\League');
    }
    
    public function scopeEarnings($query,$type)
    {
        $now = new \DateTime;
        switch ($type) {
            case 'today': 
                return $query->whereDate('updated_at','=',$now->format('Y-m-d'))
                        ->select(\DB::raw('sum(gains) as total'));
            case 'weekly':
                return $query->whereDate('updated_at','>=',$now->modify('monday this week')->format('Y-m-d'))
                        ->whereDate('updated_at','<=',$now->modify('sunday this week')->format('Y-m-d'))
                        ->select(\DB::raw('sum(gains) as total'));
            case 'monthly':
                return $query->whereDate('updated_at','>=' ,'2018-05-21')
                        ->whereMonth('updated_at','=',$now->modify('first day of this month')->format('m'))
                        ->whereYear('updated_at','=',$now->modify('first day of this month')->format('Y'))
                        ->select(\DB::raw('sum(gains) as total'));
            case 'annual':
                return $query->whereDate('updated_at','>=' ,'2018-05-21')
                        ->whereYear('updated_at','=',$now->modify('first day of this month')->format('Y'))
                        ->select(\DB::raw('sum(gains) as total'));
            case 'total':
                return $query->whereDate('updated_at','>=','2018-05-21')->select(\DB::raw('sum(gains) as total'));
            default:
                # code...
                break;
        }
    }

    public function scopeCurrentYear($query) {
        return $query->whereRaw('year(created_at) = year(now())');
    }
    
    public function scopeStartedRMB($query) {
        return $query->whereRaw("date(created_at) >= '2018-05-21'");
    }

    public static function boot()
    {
        parent::boot();
        
        static::created(function($bet) {
            $user = $bet->user;

            \App\BetHistory::create([
                'type' => 'add',
                'match_id' => $bet->match_id,
                'bet_id' => $bet->id,
                'amount' => $bet->amount,
                'user_id' => $user->id,
                'curr_credits' => $user->credits
            ]);

            if($bet->type == 'matches'){
                //updating match data 
                $match = $bet->match;
                if($match->team_a == $bet->team_id){ //increment total team a bets if bets is for team a

                    if( $match->team_a_total_bets < 1 ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->increment('team_a_total_bets', $currentBets);
                    }else{
                        $match->increment('team_a_total_bets', $bet->amount);
                    }
                    
                }else if($match->team_b == $bet->team_id){ //increment total team b bets if bets is for team b
                
                    
                    if( $match->team_b_total_bets < 1 ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->increment('team_b_total_bets', $currentBets);
                    }else{
                        $match->increment('team_b_total_bets', $bet->amount);
                    }
                    
                }else if($match->team_c == $bet->team_id){ //increment total team c bets if bets is for team c

                    if( $match->team_c_total_bets < 1 ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->increment('team_c_total_bets', $currentBets);
                    }else{
                        $match->increment('team_c_total_bets', $bet->amount);
                    }  

                }
            }

            /** credit logs */
            $credit_log = [
                'user_id'        => $user->id,
                'reference_id'   => $bet->id,
                'model'          => 'Bet',
                'action'         => 'ADD_BET',
                'amount'         => $bet->amount,
                'current_credit' => $user->credits,
                'new_credit'     => $user->credits - $bet->amount,
                'created_at'     => \DB::raw("now()"),
                'updated_at'     => \DB::raw("now()")
            ];

            // $job = (new LogUserCredits($credit_log))
            //     ->onConnection('redis')
            //     ->onQueue('low')
            //     ->delay(Carbon::now()->addMinutes(2));

            // dispatch($job);
            dispatch(new LogUserCredits($credit_log));
            /** end credit logs */
        });

        static::updated(function($bet) {
            $user = $bet->user;

            // Add to bet history
            \App\BetHistory::create([
                'type' => 'update',
                'match_id' => $bet->match_id,
                'bet_id' => $bet->id,
                'amount' => $bet->amount,
                'user_id' => $user->id,
                'curr_credits' => $user->credits
            ]);

            if($bet->type == 'matches'){
                //updating match data 
                $match = $bet->match;

                if($match->team_a == $bet->team_id){ //increment total team a bets if bets is for team a

                    if( $match->team_a_total_bets < 1 ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->increment('team_a_total_bets', $currentBets);
                    }else{
                        $match->increment('team_a_total_bets', ($bet->amount - ($bet->getOriginal('amount') ?? 0)));
                    }
                    
                }else if($match->team_b == $bet->team_id){ //increment total team b bets if bets is for team b
                
                    
                    if( $match->team_b_total_bets < 1 ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->increment('team_b_total_bets', $currentBets);
                    }else{
                        $match->increment('team_b_total_bets', ($bet->amount - ($bet->getOriginal('amount') ?? 0)));
                    }
                    
                }else if($match->team_c == $bet->team_id){ //increment total team c bets if bets is for team c
                
                    
                    if( $match->team_c_total_bets < 1 ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->increment('team_c_total_bets', $currentBets);
                    }else{
                        $match->increment('team_c_total_bets', ($bet->amount - ($bet->getOriginal('amount') ?? 0)));
                    }
                    
                }
            }

            /** credit logs */
            $credit_log = [
                'user_id'        => $user->id,
                'reference_id'   => $bet->id,
                'model'          => 'Bet',
                'action'         => 'INCREASE_BET',
                'amount'         => $bet->amount - $bet->getOriginal('amount'),
                'current_credit' => $user->credits,
                'new_credit'     => $user->credits - ($bet->amount - $bet->getOriginal('amount')),
                'created_at'     => \DB::raw("now()"),
                'updated_at'     => \DB::raw("now()")
            ];

            // $job = (new LogUserCredits($credit_log))
            //     ->onConnection('redis')
            //     ->onQueue('low')
            //     ->delay(Carbon::now()->addMinutes(2));

            // dispatch($job);
            dispatch(new LogUserCredits($credit_log));
            /** end credit logs */

        });

        
        // cause a cascade delete to all children references for this user
        static::deleted(function($bet) {
            $user = $bet->user;
            
            \App\BetHistory::create([
                'type' => 'cancel',
                'match_id' => $bet->match_id,
                'bet_id' => $bet->id,
                'amount' => $bet->amount,
                'user_id' => $user->id,
                'curr_credits' => $user->credits
            ]);
            
            // $user->credits += (int)$bet->amount;
            // $user->save();
            $user->increment('credits', (int)$bet->amount);

            // \App\UpdatedMatchOddsLogs::firstOrCreate(
            //     [
            //         'match_id' => $bet->match_id,
            //         'type' => 'bet_deleted',
            //         'processed' => 0
            //     ],
            //     [
            //         'message' => "Bet deleted with ID: {$bet->id}, match: {$bet->match_id}"
            //     ]
            // );

            if($bet->type == 'matches'){
                //updating match data 
                $match = $bet->match;
                if($match->team_a == $bet->team_id){ //increment total team a bets if bets is for team a

                    if( $match->team_a_total_bets < 1 ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->team_a_total_bets = $currentBets;
                        $match->save();
                    }else{
                        $match->decrement('team_a_total_bets', $bet->amount);
                    }
                    
                }else if($match->team_b == $bet->team_id){ //increment total team b bets if bets is for team b
                
                    
                    if( $match->team_b_total_bets < 1  ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->team_b_total_bets = $currentBets;
                        $match->save();
                    }else{
                        $match->decrement('team_b_total_bets', $bet->amount);
                    }
                    
                }else if($match->team_c == $bet->team_id){ //increment total team c bets if bets is for team c
                
                    
                    if( $match->team_c_total_bets < 1  ){
                        $currentBets = $match->bets->where('team_id', $bet->team_id)->sum('amount');
                        $match->team_c_total_bets = $currentBets;
                        $match->save();
                    }else{
                        $match->decrement('team_c_total_bets', $bet->amount);
                    }
                    
                }         
            }       

            /** credit logs */
            $credit_log = [
                'user_id'        => $user->id,
                'reference_id'   => $bet->id,
                'model'          => 'Bet',
                'action'         => 'CANCEL_BET',
                'amount'         => $bet->amount,
                'current_credit' => $user->credits,
                'new_credit'     => $user->credits + $bet->amount,
                'created_at'     => \DB::raw("now()"),
                'updated_at'     => \DB::raw("now()")
            ];

            // $job = (new LogUserCredits($credit_log))
            //     ->onConnection('redis')
            //     ->onQueue('low')
            //     ->delay(Carbon::now()->addMinutes(2));

            // dispatch($job);
            dispatch(new LogUserCredits($credit_log));
            /** end credit logs */
        });
    }
}
