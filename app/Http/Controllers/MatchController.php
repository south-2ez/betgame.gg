<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\BetRepository;
use Cache;

class MatchController extends Controller
{
    
    public function viewMatch(\App\Match $match)
    {
       
        // $match->load('teamA','teamB','league');
        
        $matchTeamACacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}-team-a";
        $matchTeamBCacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}-team-b";
        
        $matchLeagueCacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}-league";

        $match->teamA = Cache::remember( md5($matchTeamACacheKey), 60 , function () use ($match) {
                            return $match->teamA;
                        });
        $match->teamB = Cache::remember( md5($matchTeamBCacheKey), 60 , function () use ($match) {
                return $match->teamB;
            });               

        //if draw betting is enabled
        if(!empty($match->team_c)){
            $matchTeamCCacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}-team-c";
            $match->teamC = Cache::remember( md5($matchTeamCCacheKey), 60 , function () use ($match) {
                    return $match->teamC;
                });               
        }
            
        $match->league = Cache::remember( md5($matchLeagueCacheKey), 60 , function () use ($match) {
                return $match->league;
            }); 


        $betRepo = new BetRepository;
        $matchBetsCacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}";
        $match_id = $match->id;


        $user = \Auth::user();
        $hasMatchManagementAccess = !empty($user) ? hasMatchManagementAccess($user) : false;

        if($hasMatchManagementAccess || ( empty($match->team_a_total_bets)  || empty($match->team_b_total_bets) ) || $match->status != 'open' ){
            $bets = $match->status == 'open' ? 
                        $betRepo->getBetsByMatchId($match->id, ['id','amount','team_id','user_id','ratio','gains']) : 
                        Cache::remember( md5($matchBetsCacheKey), 60 , function () use ($match_id, $betRepo) {
                            
                            return $betRepo->getBetsByMatchId($match_id, ['id','amount','team_id','user_id','ratio','gains','match_id']);
                        });

            $total_bets = $bets->sum('amount');
            $team_a_bets = $bets->where('team_id', $match->team_a)->sum('amount');
            $team_b_bets = $bets->where('team_id', $match->team_b)->sum('amount');

            $team_a_ratio =  $team_a_bets > 0 ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;
            $team_b_ratio = $team_b_bets > 0 ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;
            
            $team_a_percentage = $total_bets > 0 ? ($team_a_bets / $total_bets) * 100 : 0;
            $team_b_percentage = $total_bets > 0 ? ($team_b_bets / $total_bets) * 100 : 0;

            $team_a_ratio  = bcdiv($team_a_ratio, 1 ,2);
            $team_b_ratio  = bcdiv($team_b_ratio, 1 ,2);

            //if draw betting is enabled
            if(!empty($match->team_c)){
                $team_c_bets = $bets->where('team_id', $match->team_c)->sum('amount');
                $team_c_ratio = $team_c_bets > 0 ? $total_bets / $team_c_bets * (1 - $match->fee) : 0;
                $team_c_percentage = $total_bets > 0 ? ($team_c_bets / $total_bets) * 100 : 0;   
                $team_c_ratio  = bcdiv($team_c_ratio, 1 ,2);      
            }            


        }else{

            $total_bets = $match->team_a_total_bets + $match->team_b_total_bets;
            //if draw betting is enabled
            if(!empty($match->team_c)){
                $total_bets += $match->team_c_total_bets;
            }           

            $team_a_bets = $match->team_a_total_bets;
            $team_b_bets = $match->team_b_total_bets;
            
            $team_a_ratio =  $team_a_bets > 0 ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;
            $team_b_ratio = $team_b_bets > 0 ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;

            $team_a_percentage = $total_bets > 0 ? ($team_a_bets / $total_bets) * 100 : 0;
            $team_b_percentage = $total_bets > 0 ? ($team_b_bets / $total_bets) * 100 : 0;

            $team_a_ratio  = bcdiv($team_a_ratio, 1 ,2);
            $team_b_ratio  = bcdiv($team_b_ratio, 1 ,2);
        
            //if draw betting is enabled
            if(!empty($match->team_c)){
                $team_c_bets = $match->team_c_total_bets;
                $team_c_ratio = $team_c_bets > 0 ? $total_bets / $team_c_bets * (1 - $match->fee) : 0;
                $team_c_percentage = $total_bets > 0 ? ($team_c_bets / $total_bets) * 100 : 0;
                $team_c_ratio  = bcdiv($team_c_ratio, 1 ,2);      
            }  

        }

        $match_data = [
            'team_a_ratio'      => $team_a_ratio > 0.01 ? $team_a_ratio : 2,
            'team_b_ratio'      => $team_b_ratio > 0.01 ? $team_b_ratio : 2,
            'team_c_ratio'      => !empty($team_c_ratio) && $team_c_ratio > 0.01 ? $team_c_ratio : 0,
            'team_a_percentage' => !empty($team_a_percentage) ? $team_a_percentage : 50,
            'team_b_percentage' => !empty($team_b_percentage) ? $team_b_percentage : 50,
            'team_c_percentage' => !empty($team_c_percentage) ? $team_c_percentage : 0,
            'team_a_name'       => $match->teamA->name,
            'team_b_name'       => $match->teamB->name,
            'team_c_name'       => !empty($match->team_c) ? $match->teamC->name : '',
            'team_a_id'         => $match->teamA->id,
            'team_b_id'         => $match->teamB->id,
            'team_c_id'         => $match->team_c,
            'team_a_bets'       => $team_a_bets,
            'team_b_bets'       => $team_b_bets,
            'team_c_bets'       => !empty($team_c_bets) ? $team_c_bets : 0,
            'total_bets'        => $total_bets,
            'match_fee'         => $match->fee,
            'match_status'      => $match->status,
            'match_winner'      => $match->team_winner,
            'team_a_total_bets' => $match->team_a_total_bets,
            'team_b_total_bets' => $match->team_b_total_bets,
            'team_c_total_bets' => $match->team_c_total_bets,
        ];

        // dd($match_data);
        


        $match_bet = 0;


        if (\Auth::check()) {
            
            if ($hasMatchManagementAccess) {
                $team_a_bet = !empty($user) ?  $bets->where('team_id',$match->team_a)->where('user_id',$user->id)->first() : null;
                $team_b_bet = !empty($user) ? $bets->where('team_id',$match->team_b)->where('user_id',$user->id)->first() : null;

                $team_a_bet_amount = !empty($team_a_bet) ? $team_a_bet->amount : 0;
                $team_b_bet_amount = !empty($team_b_bet) ? $team_b_bet->amount : 0;
                $team_c_bet_amount = 0;

                //if draw betting is enabled
                if(!empty($match->team_c)){
                    $team_c_bet = !empty($user) ? $bets->where('team_id',$match->team_c)->where('user_id',$user->id)->first() : null;
                    $team_c_bet_amount = !empty($team_c_bet) ? $team_c_bet->amount : 0;
                }

                $match_bet =  $team_a_bet_amount + $team_b_bet_amount + $team_c_bet_amount;

                $bets->load('team');
                $bets->load('user');
                if ($match->type == 'main') {
                    $submatches = $match->subMatches;
                    $submatches = $match->subMatches->count() ? $submatches->prepend($match) : collect();
                } else {
                    $sub = \App\SubMatch::find($match->id);
                    $submatches = $sub->mainMatch->subMatches;
                    $submatches = $submatches->prepend($sub->mainMatch);
                }

                $total_team_a_payout = $team_a_bets * $team_a_ratio;
                $potential_team_a_winning = $team_a_bets ? ( $team_a_bet_amount / $team_a_bets) * $total_team_a_payout : 0;

                $total_team_b_payout = $team_b_bets * $team_b_ratio;
                $potential_team_b_winning = $team_b_bets ? ($team_b_bet_amount / $team_b_bets) * $total_team_b_payout : 0;
                
                $match_data['potential_team_a_winning'] = $potential_team_a_winning;
                $match_data['potential_team_b_winning'] = $potential_team_b_winning;

                //if draw betting is enabled
                if(!empty($match->team_c)){
                    $total_team_c_payout = $team_c_bets * $team_c_ratio;
                    $potential_team_c_winning = $team_c_bets ? ( $team_c_bet_amount / $team_c_bets) * $total_team_c_payout : 0; 
                    $match_data['potential_team_c_winning'] = $potential_team_c_winning;                                   
                }                

                $matchReport = $match->status == 'settled' ? \App\MatchReport::find($match->id) : null;

                $match_data['matchReport'] = $matchReport;

                return view('match', compact('match', 'team_a_bet', 'team_b_bet', 'team_c_bet', 'submatches', 'match_data', 'hasMatchManagementAccess','match_bet','bets'));
            } else {
                //$bet = \Auth::user()->bets->where('match_id', $match->id)->first();

                // $bet = $bets->where('user_id',$user->id)->first();

                // $bets = $match->status == 'open' ? 
                //     $betRepo->getBetsByMatchId($match->id, ['id','amount','team_id','user_id','ratio','gains']) : 
                //     Cache::remember( md5($matchBetsCacheKey), 60 , function () use ($match_id, $betRepo) {
                        
                //         return $betRepo->getBetsByMatchId($match_id, ['id','amount','team_id','user_id','ratio','gains','match_id']);
                //     });

                // $team_a_bet = !empty($user) ?  $bets->where('team_id',$match->team_a)->where('user_id',$user->id)->first() : null;
                // $team_b_bet = !empty($user) ? $bets->where('team_id',$match->team_b)->where('user_id',$user->id)->first() : null;

                // $team_a_bet_amount = !empty($team_a_bet) ? $team_a_bet->amount : 0;
                // $team_b_bet_amount = !empty($team_b_bet) ? $team_b_bet->amount : 0;
                
                // $match_bet =  $team_a_bet_amount + $team_b_bet_amount;

                $bet = \App\Bet::where([
                    'match_id' => $match->id,
                    'user_id' => $user->id
                ])->first();


                if ($match->type == 'main') {
                    $submatches = $match->subMatches;
                    $submatches = $match->subMatches->count() ? $submatches->prepend($match) : collect();
                } else {
                    $sub = \App\SubMatch::find($match->id);
                    $submatches = $sub->mainMatch->subMatches;
                    $submatches = $submatches->prepend($sub->mainMatch);
                }

                if($bet) {

                    //if draw betting is enabled
                    if(!empty($match->team_c)){
                        $match_bet = $bet->amount;
                        $team_ratio = $bet->team_id == $match->team_a ? $team_a_ratio : ( $bet->team_id == $match->team_b ? $team_b_ratio : $team_c_ratio);
                        $team_bets = $bet->team_id == $match->team_a ? $team_a_bets : ( $bet->team_id == $match->team_b ? $team_b_bets : $team_c_bets);
                    }else{
                        $match_bet = $bet->amount;
                        $team_ratio = $bet->team_id == $match->team_a ? $team_a_ratio : $team_b_ratio;
                        $team_bets = $bet->team_id == $match->team_a ? $team_a_bets : $team_b_bets;
                    }
                    
                } else {
                    $team_ratio = 0;
                    $team_bets = 0;
                }
                
                $total_team_payout      = $match_bet * $team_ratio;
                //$potential_team_winning = ($team_bets && $team_bets <> '0.00') ?  $total_team_payout : 0;

                $potential_team_winning = $total_team_payout;
                
                return view('match', compact('match', 'bet', 'submatches', 'match_data', 'team_ratio', 'potential_team_winning', 'hasMatchManagementAccess','match_bet'));
            }
        } else {
            if ($match->type == 'main') {
                $submatches = $match->subMatches;
                $submatches = $match->subMatches->count() ? $submatches->prepend($match) : collect();
            } else {
                $sub = \App\SubMatch::find($match->id);
                $submatches = $sub->mainMatch->subMatches;
                $submatches = $submatches->prepend($sub->mainMatch);
            }
            
            return view('match', compact('match', 'submatches', 'match_data','hasMatchManagementAccess','match_bet'));
        }
    } 


    public function viewMatchOld(\App\Match $match)
    {
    
        $match->load('teamA','teamB','league');
        $betRepo = new BetRepository;
        $matchBetsCacheKey = "{$match->status}-match-{$match->id}-{$match->updated_at}";
        $match_id = $match->id;

        $bets = $match->status == 'open' ? 
                    $betRepo->getBetsByMatchId($match->id, ['id','amount','team_id','user_id','ratio','gains']) : 
                    Cache::remember( md5($matchBetsCacheKey), 60 , function () use ($match_id, $betRepo) {
                        
                        return $betRepo->getBetsByMatchId($match_id, ['id','amount','team_id','user_id','ratio','gains','match_id']);
                    });

       
        $user = \Auth::user();
        
        $total_bets = $bets->sum('amount');
        $team_a_bets = $bets->where('team_id', $match->team_a)->sum('amount');
        $team_b_bets = $bets->where('team_id', $match->team_b)->sum('amount');
        
        $team_a_ratio =  $team_a_bets > 0 ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;
        $team_b_ratio = $team_b_bets > 0 ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;

        $team_a_percentage = $total_bets > 0 ? ($team_a_bets / $total_bets) * 100 : 0;
        $team_b_percentage = $total_bets > 0 ? ($team_b_bets / $total_bets) * 100 : 0;

        $team_a_ratio  = bcdiv($team_a_ratio, 1 ,2);
        $team_b_ratio  = bcdiv($team_b_ratio, 1 ,2);
        

        $match_data = [
            'team_a_ratio' => $bets->count() ? $team_a_ratio : 2,
            'team_b_ratio' => $bets->count() ? $team_b_ratio : 2,
            'team_a_percentage' => $bets->count() ? $team_a_percentage : 50,
            'team_b_percentage' => $bets->count() ? $team_b_percentage : 50,
            'team_a_name' => $match->teamA->name,
            'team_b_name' => $match->teamB->name,
            'team_a_id' => $match->teamA->id,
            'team_b_id' => $match->teamB->id,
            'team_a_bets' => $team_a_bets,
            'team_b_bets' => $team_b_bets,
            'total_bets' => $total_bets,
            'match_fee' => $match->fee,
            'match_status' => $match->status,
            'match_winner' => $match->team_winner,
            'team_a_total_bets' => $match->team_a_total_bets,
            'team_b_total_bets' => $match->team_b_total_bets,
        ];

        // dd($match_data);
        
        $team_a_bet = !empty($user) ?  $bets->where('team_id',$match->team_a)->where('user_id',$user->id)->first() : null;
        $team_b_bet = !empty($user) ? $bets->where('team_id',$match->team_b)->where('user_id',$user->id)->first() : null;

        $team_a_bet_amount = !empty($team_a_bet) ? $team_a_bet->amount : 0;
        $team_b_bet_amount = !empty($team_b_bet) ? $team_b_bet->amount : 0;
        
        $match_bet =  $team_a_bet_amount + $team_b_bet_amount;

        $hasMatchManagementAccess = false;
        if (\Auth::check()) {
            $hasMatchManagementAccess = !empty($user) ? hasMatchManagementAccess($user) : false;
            if ($hasMatchManagementAccess) {
                $bets->load('team');
                $bets->load('user');
                if ($match->type == 'main') {
                    $submatches = $match->subMatches;
                    $submatches = $match->subMatches->count() ? $submatches->prepend($match) : collect();
                } else {
                    $sub = \App\SubMatch::find($match->id);
                    $submatches = $sub->mainMatch->subMatches;
                    $submatches = $submatches->prepend($sub->mainMatch);
                }

                $total_team_a_payout = $team_a_bets * $team_a_ratio;
                $potential_team_a_winning = $team_a_bets ? ( $team_a_bet_amount / $team_a_bets) * $total_team_a_payout : 0;

                $total_team_b_payout = $team_b_bets * $team_b_ratio;
                $potential_team_b_winning = $team_b_bets ? ($team_b_bet_amount / $team_b_bets) * $total_team_b_payout : 0;
                
                $match_data['potential_team_a_winning'] = $potential_team_a_winning;
                $match_data['potential_team_b_winning'] = $potential_team_b_winning;

                $matchReport = $match->status == 'settled' ? \App\MatchReport::find($match->id) : null;

                $match_data['matchReport'] = $matchReport;

                return view('match', compact('match', 'team_a_bet', 'team_b_bet', 'submatches', 'match_data', 'hasMatchManagementAccess','match_bet','bets'));
            } else {
                //$bet = \Auth::user()->bets->where('match_id', $match->id)->first();

                $bet = $bets->where('user_id',$user->id)->first();
                if ($match->type == 'main') {
                    $submatches = $match->subMatches;
                    $submatches = $match->subMatches->count() ? $submatches->prepend($match) : collect();
                } else {
                    $sub = \App\SubMatch::find($match->id);
                    $submatches = $sub->mainMatch->subMatches;
                    $submatches = $submatches->prepend($sub->mainMatch);
                }
                if($bet) {
                    $team_ratio = $bet->team_id == $match->team_a ? $team_a_ratio : $team_b_ratio;
                    $team_bets = $bet->team_id == $match->team_a ? $team_a_bets : $team_b_bets;
                } else {
                    $team_ratio = 0;
                    $team_bets = 0;
                }
                $total_team_payout = $team_bets * $team_ratio;
                $potential_team_winning = $team_bets ? ($match_bet / $team_bets) * $total_team_payout : 0;
                return view('match', compact('match', 'bet', 'submatches', 'match_data', 'team_ratio', 'potential_team_winning', 'hasMatchManagementAccess','match_bet'));
            }
        } else {
            if ($match->type == 'main') {
                $submatches = $match->subMatches;
                $submatches = $match->subMatches->count() ? $submatches->prepend($match) : collect();
            } else {
                $sub = \App\SubMatch::find($match->id);
                $submatches = $sub->mainMatch->subMatches;
                $submatches = $submatches->prepend($sub->mainMatch);
            }
            return view('match', compact('match', 'submatches', 'match_data','hasMatchManagementAccess','match_bet'));
        }
    }

    public function getOpenLiveMatches($type = 'all', $offset = 0, $take = 5)
    {
        switch($type){
            case 'csgo':
                  $matches = \App\Match::mainMatches()->csgoMatches()->whereIn('matches.status',['open','ongoing'])->with('teamA','teamB','league')->orderBy('schedule', 'asc')->offset($offset)->take($take)->get();
                break;
            case 'dota2':
                  $matches = \App\Match::mainMatches()->dota2Matches()->whereIn('matches.status',['open','ongoing'])->with('teamA','teamB','league')->orderBy('schedule', 'asc')->offset($offset)->take($take)->get();
                break;
            case 'sports':
                  $matches = \App\Match::mainMatches()->sportsMatches()->whereIn('matches.status',['open','ongoing'])->with('teamA','teamB','league')->orderBy('schedule', 'asc')->offset($offset)->take($take)->get();
                break;
            case 'all':
            default:
                $matches = \App\Match::mainMatches()->whereIn('matches.status',['open','ongoing'])->with('teamA','teamB','league')->orderBy('schedule', 'asc')->offset($offset)->take($take)->get();
                break;
        }
        // $matches = \App\Match::with('league', 'teamA','teamB','bets')->whereIn('status',['open','ongoing'])
        $offset += $take;

        $matches->sortBy('schedule');
        
        $returnMatches = [];
        $matchTeamRatiosCacheKey = md5('for-home-page-active-matches-ratio');
        $matchCachedRatios = Cache::get($matchTeamRatiosCacheKey); //get cachced match team ratios
        if(!empty($matches)){
            foreach($matches as $key => $match){

                $cachedRatio = $matchCachedRatios[$match->id];
                $team_a_percentage = !empty($cachedRatio) ? $cachedRatio->teama_percentage : 50.00;
                $team_b_percentage = !empty($cachedRatio) ? $cachedRatio->teamb_percentage : 50.00;

                // $matchRatio = calculateMatchTeamRatios($match, $match->bets);
                $match->ratio = [
                    // 'team_a_ratio' => $matchRatio['team_a_ratio'],
                    // 'team_b_ratio' => $matchRatio['team_b_ratio'],
                    'team_a_percentage' => $team_a_percentage,
                    'team_b_percentage' => $team_b_percentage,
                ];


                $returnMatches[] = $match;
            }
        }


        return [
            'matches' => $returnMatches,
            'offset' => $offset
        ];

    }

    public function mockSendMatchReport(){
        
        $match_id = 1825;
        $betRepo = new BetRepository;
        $match = \App\Match::find($match_id)->load('teamA','teamB');

        $total_team_a_bettors=0;
        $total_team_a_bets=0;
        $total_team_a_profit=0;
        $total_team_b_bettors=0;
        $total_team_b_bets=0;
        $total_team_b_profit=0;
        $user_a = collect();
        $user_b = collect();

        if($match->team_a == $match->team_winner && $match->status =='settled'){
            $status_a = 'win';
        }elseif ($match->status =='draw'){
            $status_a = 'draw';
        }elseif ($match->team_a != $match->team_winner && $match->status =='settled') {
            $status_a = 'lost';
        }elseif ($match->status =='open' || $match->status =='ongoing') {
            $status_a = 'ongoing';  
        }elseif ($match->status =='cancelled'){
            $status_a = 'cancelled';
        }

        $bets = $betRepo->getBetsByMatchId($match->id, ['id','amount','team_id','user_id','gains']);
        $bets->load('team');
        $bets->load('user');   

        $teamAbets = $bets->where('team_id',$match->team_a);
        $teamBbets = $bets->where('team_id',$match->team_b);

        $total_bets = $bets->sum('amount');
        $total_team_a_bets = $teamAbets->sum('amount');
        $total_team_b_bets = $teamBbets->sum('amount');

        $total_team_a_bettors = $teamAbets->count();
        $total_team_b_bettors = $teamBbets->count();

        foreach ($teamAbets as $bet) {

            $profit = ($bet->amount * $bet->ratio )- $bet->amount;
            if($bet->team_id == $match->team_winner && $match->status =='settled'){
                $total_team_a_profit+=$profit;
                $status_a = 'win';
                $_profit = $profit;
            }elseif ($match->status =='draw'){
                $status_a = 'draw';
                $_profit = 0.00;
            }elseif ($bet->team_id != $match->team_winner && $match->status =='settled') {
                $total_team_a_profit+=$bet->amount;
                $status_a = 'lost';
                $_profit = $bet->amount;
            }elseif ($match->status =='open' || $match->status =='ongoing') {
                $status_a = 'ongoing';
                $_profit = 0.00;
            }elseif ($match->status =='cancelled'){
                $status_a = 'cancelled';
                $_profit = 0.00;
            }
            $user_a->push([
                'id' => $bet->user->id,
                'name' => $bet->user->name,
                'amount' => $bet->amount,
                'profit' => $_profit,
            ]);
            
        }
        $team_a = collect([
            'users' => $user_a,
            'total_bettors' => $total_team_a_bettors,
            'total_bet' => $total_team_a_bets,
            'average_bet' => $total_team_a_bettors != 0 ? ($total_team_a_bets/$total_team_a_bettors) : 0.00,
            'total_profit' => $total_team_a_profit,
            'status' =>  $status_a,
            'id' => $match->team_a,
            'name' => $match->teamA->name,
        ]);

        if($match->team_b == $match->team_winner && $match->status =='settled'){
            $status_b = 'win';
        }elseif ($match->status =='draw'){
            $status_b = 'draw';
        }elseif ($match->team_b != $match->team_winner && $match->status =='settled') {
            $status_b = 'lost';
        }elseif ($match->status =='open' || $match->status =='ongoing') {
            $status_b = 'ongoing';
        }elseif ($match->status =='cancelled'){
            $status_b = 'cancelled';
        }

        foreach ($teamBbets as $bet) {

            $profit = ($bet->amount * $bet->ratio )- $bet->amount;
            if($bet->team_id == $match->team_winner && $match->status =='settled'){
                $total_team_b_profit+=$profit;
                $status_b = 'win';
                $_profit = $profit;
            }elseif ($match->status =='draw'){
                $status_b = 'draw';
                $_profit = 0.00;
            }elseif ($bet->team_id != $match->team_winner && $match->status =='settled') {
                $total_team_b_profit+=$bet->amount;
                $status_b = 'lost';
                $_profit = $bet->amount;
            }elseif ($match->status =='open' || $match->status =='ongoing') {
                $status_b = 'ongoing';
                $_profit = 0.00;
            }elseif ($match->status =='cancelled'){
                $status_b = 'cancelled';
                $_profit = 0.00;
            }
            $user_b->push([
                'id' => $bet->user->id,
                'name' => $bet->user->name,
                'amount' => $bet->amount,
                'profit' => $_profit,
            ]);

        }

        $team_b = collect([
            'users' => $user_b,
            'total_bettors' => $total_team_b_bettors,
            'total_bet' => $total_team_b_bets,
            'average_bet' => $total_team_b_bettors != 0 ? ($total_team_b_bets/$total_team_b_bettors) : 0.00,
            'total_profit' => $total_team_b_profit,
            'status' =>  $status_b,
            'id' => $match->team_b,
            'name' => $match->teamB->name,
        ]);

        switch ($match->status) {
            case 'draw':
                $collected_fees = 0.00;
                $total_payout = 0.00;
                break;
            case 'open':
                $collected_fees = 0.00;
                $total_payout = 0.00;
                break;
            case 'settled':
                $collected_fees = $total_bets*$match->fee;
                $total_payout = $total_bets-$collected_fees;
                break;
            case 'ongoing':
                $collected_fees = $total_bets*$match->fee;
                $total_payout = $total_bets-$collected_fees;
                break;
            case 'cancelled':
                $collected_fees = 0.00;
                $total_payout = 0.00;
                break;
            
            default:
                # code...
                break;
        }
        $data = collect([
            'team_a' => $team_a,
            'team_b' => $team_b,
        ]);

        $total_circulating_credits = number_format(str_replace(",","",getCirculatingCredits()),2,'.','');
        
        MatchReport::create([
            'id' => $match->id,
            'league_id' => $match->league_id,
            'status' => $match->status,
            'name' => $match->name,
            'label' => $match->label,
            'settled_by' =>$this->data['settled_by'],
            'data' => $data->toJson(),
            'total_bettors' => ($total_team_b_bettors+$total_team_a_bettors),
            'total_match_bet' => $total_bets,
            'average_match_bet' => $total_team_b_bettors != 0 || $total_team_a_bettors != 0 ? ($total_bets/($total_team_b_bettors+$total_team_a_bettors)) : 0.00,
            'match_fee' => $match->fee,
            'total_fees_collected' => $collected_fees,
            'total_payout' => $total_payout,
            'circulating_credits_before_settled' => number_format(str_replace(",","",$this->data['total_circulating_credits']),2,'.',''),
            'circulating_credits_after_settled' => $total_circulating_credits
        ]);
        
        $fee = new Fee;
        $fee->meta_key = 'match';
        $fee->meta_value = $match->id;
        $fee->percent = $match->fee;
        $fee->collected =  $collected_fees;
        $fee->save();
        
        $_match = MatchReport::find($match->id);
        Log::info('Sending '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.$_match->created_at.' match report | ID: '.$_match->id.'...');
        $excelFile = \Excel::create('Match Report of '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.date('Y-m-d',strtotime($_match->created_at)), function($excel) use($_match) {
            $excel->sheet('Overview', function($sheet) use($_match) {
                $sheet->loadView('emails.match-report', array('match' => $_match));
            });
        });
                
        \Mail::raw('See attached excel file for the report. | Match Name: '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' | Match ID : '.$_match->id.' | Date: '.$_match->created_at.' | Settled By: '.$_match->settledBy->name, function($message) use ($excelFile,$_match) {
            $message->from('admin@2ez.bet', '2ez.bet Admin');
            $message->to('admin@2ez.bet')
            ->cc('brandnewbien@gmail.com')
            ->cc('south.2ez@gmail.com')
            ->subject('Match Report of '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.$_match->created_at);
            $message->attach($excelFile->store("xlsx", false, true)['full']);
        });

        Log::info('Sent match Report of '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.$_match->created_at);
 

    }

    public function downloadAffliateVoucherCodeUsers(Request $request)
    {

        if(!empty(\Auth::user()->voucher_code)){
            $affiliate = \Auth::user();


        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $validator = \Validator::make($request->all(),$rules);
        if ($validator->passes()) {

            $from_date = $request->fromDate;
            $to_date = $request->toDate;

            $where = [
                [ 'date_settled', '>=', $from_date ],
                [ 'date_settled', '<=', $to_date ],
                [ 'type', '=', 'own']
            ];

            $filtered = $affiliate->commissionBets()->where($where)->get();
            $users = [];

            if(!empty($filtered)){
                foreach($filtered as $commission){
                    $usersArray = (array) $commission->user_bets;
                    $users = array_merge($users,  array_column($usersArray,'name') );
                }

                return  response()->json(
                    [
                        'success' => true,
                        'users' => is_array($users) ?  array_unique($users) : array_unique( (array) $users)
                    ]);    

            }else{
                return  response()->json(
                    [
                        'success' => false,
                        'message' => 'No users found.',
                    ]);                
            }

        }else{
            return  response()->json(
                [
                    'success' => false,
                    'message' => 'From and until/to date are both required.'
                ]);
        }



        }else{
            return  response()->json(
                [
                    'success' => false,
                    'message' => '403 Forbidden.'
                ]);
        }
    }

    public function getAffliateNewVoucherCodeUsers(Request $request)
    {

        if(!empty(\Auth::user()->voucher_code)){
            $affiliate = \Auth::user();


        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $voucher_code = $affiliate->voucher_code;

        $validator = \Validator::make($request->all(),$rules);
        if ($validator->passes()) {

            $from_date = $request->fromDate;
            $to_date = $request->toDate;

            $where = [
                [ 'created_at', '>=', $from_date ],
                [ 'created_at', '<=', $to_date ],
                [ 'redeem_voucher_code','=',$voucher_code]
            ];

            $filtered = \App\User::select(['id','name'])->where($where)->get();
            $users = [];

            if(!empty($filtered)){

                return  response()->json(
                    [
                        'success' => true,
                        'users' => $filtered->pluck('name'),
                    ]);    

            }else{
                return  response()->json(
                    [
                        'success' => false,
                        'message' => 'No users found.',
                    ]);                
            }

        }else{
            return  response()->json(
                [
                    'success' => false,
                    'message' => 'From and until/to date are both required.'
                ]);
        }



        }else{
            return  response()->json(
                [
                    'success' => false,
                    'message' => '403 Forbidden.'
                ]);
        }        
    }

}
