<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\League;
use App\Match;
use App\Team;
use Carbon\Carbon;
use Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('auth', ['except' => 
                                            [
                                                'index', 
                                                'csgoindex', 
                                                'sportsindex', 
                                                'dota2index',
                                                'lolindex', 
                                                'nbaplayoffsindex', 
                                                'allmatches',
                                                'showMoreMatches', 
                                                'showCsgoMatches', 
                                                'showDotaMatches', 
                                                'showSportsMatches', 
                                                'showLolMatches',
                                                'showNbaPlayoffsMatches',
                                                'displayItemMarket', 
                                                'showRecentMatches',
                                                'userAffliation',
                                                'reactivateAccount'
                                            ]
                                    ]
                        );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //dd(\App\User::admins());

        $leagueTeamsRatiosCacheKey = md5('for-home-page-active-leagues-ratio');
        $matchTeamRatiosCacheKey = md5('for-home-page-active-matches-ratio');

        $leaguesCachedRatios = Cache::get($leagueTeamsRatiosCacheKey); //get cached tournament teams ratio 
        $matchCachedRatios = Cache::get($matchTeamRatiosCacheKey); //get cachced match team ratios

        // dd($leaguesCachedRatios);
        $leagues = League::active()->with('champion')->orderby('display_order','asc')->get();
        
        $nbaPlayoffsLeagues = League::select('id')->where('type','nbaplayoffs')->get()->pluck('id');
                
        //if cache is not empty, then use this; no need to query;
        if(empty($leaguesCachedRatios)){

            $openOutrightBettings = $leagues->where('betting_status',1);
            $closedOutrightBettings = $leagues->where('betting_status',0);
            $settledOutrightBettings = $leagues->where('betting_status',-1);
    
            $openOutrightBettings_ids = $openOutrightBettings->pluck('id')->sort();
            $closedOutrightBettings_ids = $closedOutrightBettings->pluck('id')->sort();
            $settledOutrightBettings_ids = $settledOutrightBettings->pluck('id')->sort();
    
            $openOutrightCacheKeys = "open-outright-bettings-{$openOutrightBettings_ids->implode('_')}";
            $closedOutrightCacheKeys = "closed-outright-bettings-{$closedOutrightBettings_ids->implode('_')}";
            $settledOutrightCacheKeys = "settled-outright-bettings-{$settledOutrightBettings_ids->implode('_')}";
    
    
            
            $openOutrightBets = \App\Bet::selectRaw("amount, team_id, league_id")
                                            ->whereIn('league_id', $openOutrightBettings_ids)
                                            ->where('type', 'tournament')
                                            ->get();
    
    
            $closedOutrightBets = Cache::remember( md5($closedOutrightCacheKeys), 60 , function () use ($closedOutrightBettings_ids) {
                
                return \App\Bet::selectRaw("amount, team_id, league_id")
                                    ->whereIn('league_id', $closedOutrightBettings_ids)
                                    ->where('type', 'tournament')
                                    ->get();
            });
    
            $settledOutrightBets = Cache::remember( md5($settledOutrightCacheKeys), 60 , function () use ($settledOutrightBettings_ids) {
    
                return \App\Bet::selectRaw("amount, team_id, league_id")
                                    ->whereIn('league_id', $settledOutrightBettings_ids)
                                    ->where('type', 'tournament')
                                    ->get();
    
            });
    
            $leagueBets = collect( 
                                array_merge(
                                    $openOutrightBets->all(), 
                                    array_merge( 
                                        $closedOutrightBets->all(),
                                        $settledOutrightBets->all() 
                                    )
                                )
        
                            );
                    
    
            foreach ($leagues as $_l) {
    
                $leagueId =  $_l->id;
                $total_bets = $leagueBets->where('league_id', $leagueId)->sum('amount');
    
                $leagueTeamsCacheKey = "league-teams-{$leagueId}";
    
    
                $leagueTeams =  Cache::remember( md5($leagueTeamsCacheKey), 60 , function () use ($_l) {
                    return $_l->teams()->get();
        
                });
                        
                $_l->teams = $leagueTeams;
    
                foreach($leagueTeams as $tid => $team) {
                    $team_bets = $leagueBets->where('league_id', $_l->id)->where('team_id', $team->id)->sum('amount');
                    $_l->teams[$tid]->teamPercentage = $total_bets ? ($team_bets / $total_bets) * 100 : 0;
                    $_l->teams[$tid]->teamRatio = $team_bets ? $total_bets / $team_bets * (1 - $_l->betting_fee) : 0;
                }
            }

        }else{ // use cache

            foreach ($leagues as $_l) {
    
                $leagueId =  $_l->id;
                $leagueTeamsCacheKey = "league-teams-{$leagueId}";
                $cachedRatio = !empty($leaguesCachedRatios[$leagueId]) ? $leaguesCachedRatios[$leagueId] : null;
    
                $leagueTeams =  Cache::remember( md5($leagueTeamsCacheKey), 60 , function () use ($_l) {
                    return $_l->teams()->get();
                });
                        
                $_l->teams = $leagueTeams;
    
                foreach($leagueTeams as $tid => $team) {
                    $teamRatio =  !empty($cachedRatio) ? $cachedRatio[$team->id] : null;
                    $_l->teams[$tid]->teamPercentage = !empty( $teamRatio ) ? $teamRatio->teamPercentage : 0;
                    $_l->teams[$tid]->teamRatio = !empty( $teamRatio ) ? $teamRatio->teamRatio : 0;
                }
            }

        }



        $matches = Match::mainMatches()->whereIn('matches.status',['open','ongoing'])->orderBy('schedule', 'asc')->with('teamA','teamB','league');
        //$_matches = $matches->take(10)->get();
        $_matches = $matches->get();
        $liveList = collect();
        $openList = collect();
        $oldList = collect();
        // $maxTotal = $matches->count();
        $maxTotal = 9999;

        if(empty($matchCachedRatios)){ //if cache is empty; then query it

            $openMatches = $_matches->where('status','open');
            $liveMatches = $_matches->where('status','ongoing');
    
            $openMatches_ids = $openMatches->pluck('id')->sort();
            $liveMatches_ids = $liveMatches->pluck('id')->sort();
    
            $liveMatchesCacheKeys = "live-matches-{$liveMatches_ids->implode('_')}";
    
    
    
            $liveMatchesBets = Cache::remember( md5($liveMatchesCacheKeys), 60 , function () use ($liveMatches_ids) {
    
                return \App\Bet::selectRaw("SUM(amount) AS total_bets, team_id, match_id")
                                    ->whereIn('match_id', $liveMatches_ids)
                                    ->groupBy('match_id', 'team_id')->get();
            });
    
    
    
    
            $openMatchesBets = \App\Bet::selectRaw("SUM(amount) AS total_bets, team_id, match_id")
                                        ->whereIn('match_id', $openMatches_ids)
                                        ->groupBy('match_id', 'team_id')->get();
    
            // $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
            //             ->selectRaw("SUM(amount) AS total_bets, team_id, match_id")
            //             ->groupBy('match_id', 'team_id')->get();
    
            $matchBets = collect( 
                array_merge(
                    $liveMatchesBets->all(), 
                    $openMatchesBets->all()
                )
    
            );
        
                        
            foreach ($_matches as $_m) {
                $match_details = $matchBets->where('match_id', $_m->id);
                $team_a_percentage = 50;
                $team_b_percentage = 50;
                $team_c_percentage = 0;

                if($match_details->count()) {
                    $total_bets = $matchBets->where('match_id', $_m->id)->sum('total_bets');
                    $team_a_bets = $matchBets->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                    $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;
    
                    $team_b_bets = $matchBets->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                    $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                    $team_c_bets = $matchBets->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                    $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;    
                    
                
                }
                $_m->teama_percentage = number_format($team_a_percentage, 2);
                $_m->teamb_percentage = number_format($team_b_percentage, 2);
                $_m->teamc_percentage = number_format($team_c_percentage, 2);

                if ($_m->schedule->isFuture()) {
                    if($_m->status == 'ongoing')
                        $liveList->push($_m);
                    else if($_m->status == 'open')
                        $openList->push($_m);
                    else
                        $oldList->push($_m);
                } else {
                    if ($_m->status == 'ongoing')
                        $liveList->push($_m);
                    else if($_m->status == 'open')
                        $openList->push($_m);
                    else
                        $oldList->push($_m);
                }
            }
    
    
        }else{ //use match ratio cached
        
            foreach ($_matches as $_m) {

                $cachedRatio = !empty($matchCachedRatios[$_m->id]) ? $matchCachedRatios[$_m->id] : null;
                $team_a_percentage = !empty($cachedRatio) ? $cachedRatio->teama_percentage : 50;
                $team_b_percentage = !empty($cachedRatio) ? $cachedRatio->teamb_percentage : 50;
                $team_c_percentage = !empty($cachedRatio) && !empty($cachedRatio->teamc_percentage) ? $cachedRatio->teamc_percentage : 0;

                $_m->teama_percentage = number_format($team_a_percentage, 2);
                $_m->teamb_percentage = number_format($team_b_percentage, 2);
                $_m->teamc_percentage = number_format($team_c_percentage, 2);

                if ($_m->schedule->isFuture()) {
                    if($_m->status == 'ongoing')
                        $liveList->push($_m);
                    else if($_m->status == 'open')
                        $openList->push($_m);
                    else
                        $oldList->push($_m);
                } else {
                    if ($_m->status == 'ongoing')
                        $liveList->push($_m);
                    else if($_m->status == 'open')
                        $openList->push($_m);
                    else
                        $oldList->push($_m);
                }
            }

        }
        

        $_liveList       = $liveList->whereNotIn('league_id', $nbaPlayoffsLeagues)->sortBy('schedule');
        $matches         = $_liveList->merge($openList->sortBy('schedule'));
        $teams           = !empty($teams) ? $teams : null;                                               //this is just to fix the error of compact for teams 
        $pending_deposit = $this->fetchPendingDeposits();

        return view('home', compact('teams', 'matches', 'leagues', 'maxTotal', 'pending_deposit'));
    }

    //All matches index
    public function allmatches($ptr)
    {
        $_matches = Match::mainMatches()->notNbaPlayoffsMatches()->whereIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->get()->load('league','teamA', 'teamB', 'teamC');
        $liveList = collect();
        $openList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teama_percentage = number_format($team_a_percentage, 2);
            $_m->teamb_percentage = number_format($team_b_percentage, 2);
            $_m->teamc_percentage = number_format($team_c_percentage, 2);

            if ($_m->schedule->isFuture()) {
                if($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            } else {
                if ($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            }
        }
        $_liveList = $liveList->sortBy('schedule');
        $allmatches = $_liveList->merge($openList->sortBy('schedule'));
        return [
            'matches' => $allmatches,
            'pointer' => $ptr
        ];
        //view('allmatch', compact('teams', 'matches', 'leagues'));
    }
    public function showRecentMatches() {
        $data = collect();
        switch(request()->type) {
            case 'all':
                $data = \App\Match::select('*')->mainMatches()->whereNotIn('matches.status', ['open', 'ongoing'])->orderBy('schedule', 'desc')->limit(15)->get()->load('teamA', 'teamB', 'teamC');
                break;
            case 'dota2':
                $data = \App\Match::mainMatches()->dota2Matches()->whereNotIn('matches.status', ['open', 'ongoing'])->orderBy('schedule', 'desc')->limit(15)->get()->load('teamA', 'teamB', 'teamC');
                break;
            case 'csgo':
                $data = \App\Match::mainMatches()->csgoMatches()->whereNotIn('matches.status', ['open', 'ongoing'])->orderBy('schedule', 'desc')->limit(15)->get()->load('teamA', 'teamB', 'teamC');
                break;
            case 'lol':
                 $data = \App\Match::lolMatches()->mainMatches()->whereNotIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->limit(15)->get()->load('teamA', 'teamB', 'teamC');
                break;
            case 'sports':
                $data = \App\Match::mainMatches()->sportsMatches()->whereNotIn('matches.status', ['open', 'ongoing'])->orderBy('schedule', 'desc')->limit(15)->get()->load('teamA', 'teamB', 'teamC');
                break;
        }
        return $data;
    }
    public function dota2index($ptr)
    {
        $_matches = Match::dota2Matches()->mainMatches()->whereIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->get()->load('league','teamA', 'teamB', 'teamC');
        $liveList = collect();
        $openList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teama_percentage = number_format($team_a_percentage, 2);
            $_m->teamb_percentage = number_format($team_b_percentage, 2);
            $_m->teamc_percentage = number_format($team_c_percentage, 2);

            if ($_m->schedule->isFuture()) {
                if($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            } else {
                if ($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            }
        }
        $_liveList = $liveList->sortBy('schedule');
        $dotamatches = $_liveList->merge($openList->sortBy('schedule'));
        return [
            'matches' => $dotamatches,
            'pointer' => $ptr
        ];
    }

    //added csgo index tab
    public function csgoindex($ptr)
    {
        $_matches = Match::csgoMatches()->mainMatches()->whereIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->get()->load('league','teamA', 'teamB', 'teamC');
        $liveList = collect();
        $openList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;

            }
            $_m->teama_percentage = number_format($team_a_percentage, 2);
            $_m->teamb_percentage = number_format($team_b_percentage, 2);
            $_m->teamc_percentage = number_format($team_c_percentage, 2);

            if ($_m->schedule->isFuture()) {
                if($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            } else {
                if ($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            }
        }
        $_liveList = $liveList->sortBy('schedule');
        $csgomatches = $_liveList->merge($openList->sortBy('schedule'));
        return [
            'matches' => $csgomatches,
            'pointer' => $ptr
            ];//view('csgo', compact('teams', 'csgomatches', 'leagues'));
    }
    //added sports tab index
    public function sportsindex($ptr)
    {
        $_matches = Match::sportsMatches()->mainMatches()->whereIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->get()->load('league','teamA', 'teamB', 'teamC');
        $liveList = collect();
        $openList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teama_percentage = number_format($team_a_percentage, 2);
            $_m->teamb_percentage = number_format($team_b_percentage, 2);
            $_m->teamc_percentage = number_format($team_c_percentage, 2);

            if ($_m->schedule->isFuture()) {
                if($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            } else {
                if ($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            }
        }
        $_liveList = $liveList->sortBy('schedule');
        $sportsmatches = $_liveList->merge($openList->sortBy('schedule'));

        return [
            'matches' => $sportsmatches,
            'pointer' => $ptr
        ];//view('sports', compact('teams', 'sportsmatches', 'leagues'));
    }

    //added lol tab index
    public function lolindex($ptr)
    {
 
        $_matches = Match::lolMatches()->mainMatches()->whereIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->get()->load('league','teamA', 'teamB', 'teamC');
        $liveList = collect();
        $openList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;
            
            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teama_percentage = number_format($team_a_percentage, 2);
            $_m->teamb_percentage = number_format($team_b_percentage, 2);
            $_m->teamc_percentage = number_format($team_c_percentage, 2);

            if ($_m->schedule->isFuture()) {
                if($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            } else {
                if ($_m->status == 'ongoing')
                    $liveList->push($_m);
                else if($_m->status == 'open')
                    $openList->push($_m);
                else
                    $oldList->push($_m);
            }
        }
        $_liveList = $liveList->sortBy('schedule');
        $sportsmatches = $_liveList->merge($openList->sortBy('schedule'));

        return [
            'matches' => $sportsmatches,
            'pointer' => $ptr
        ];//view('sports', compact('teams', 'sportsmatches', 'leagues'));
    }

    //added nbaplayoffs tab index
    public function nbaplayoffsindex($ptr){
            $_matches = Match::nbaPlayoffsMatches()->mainMatches()->whereIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->get()->load('league','teamA', 'teamB', 'teamC');
            $liveList = collect();
            $openList = collect();
            $oldList = collect();
            $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                        ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                        ->groupBy('match_id', 'team_id')->get();
            foreach ($_matches as $_m) {
                $match_details = $query->where('match_id', $_m->id);
                $team_a_percentage = 50;
                $team_b_percentage = 50;
                $team_c_percentage = 0;

                if($match_details->count()) {
                    $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                    $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                    $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;
    
                    $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                    $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                    $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                    $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                    
                }
                $_m->teama_percentage = number_format($team_a_percentage, 2);
                $_m->teamb_percentage = number_format($team_b_percentage, 2);
                $_m->teamc_percentage = number_format($team_c_percentage, 2);

                if ($_m->schedule->isFuture()) {
                    if($_m->status == 'ongoing')
                        $liveList->push($_m);
                    else if($_m->status == 'open')
                        $openList->push($_m);
                    else
                        $oldList->push($_m);
                } else {
                    if ($_m->status == 'ongoing')
                        $liveList->push($_m);
                    else if($_m->status == 'open')
                        $openList->push($_m);
                    else
                        $oldList->push($_m);
                }
            }
            $_liveList = $liveList->sortBy('schedule');
            $sportsmatches = $_liveList->merge($openList->sortBy('schedule'));
    
            return [
                'matches' => $sportsmatches,
                'pointer' => $ptr
            ];//view('sports', compact('teams', 'sportsmatches', 'leagues'));
        }
    
    public function showMoreMatches($ptr) {
        $_matches = Match::mainMatches()->whereNotIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->offset($ptr)->limit(10)->get()->load('league','teamA', 'teamB', 'teamC');
        $currList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $index => $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teamawin_percentage = number_format($team_a_percentage, 2);
            $_m->teambwin_percentage = number_format($team_b_percentage, 2);
            $_m->teamcwin_percentage = number_format($team_c_percentage, 2);

            // $_matches[$index]->team_a_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamA->id), 2);
            // $_matches[$index]->team_b_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamB->id), 2);
            $_matches[$index]->match_sched = $_m->schedule->diffForHumans();
            unset($_matches[$index]->fee);
            
            switch($_m->status) {
                case 'open':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = '';
                case 'ongoing':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>';
                    break;
                case 'settled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>';
                    $_matches[$index]->team_a_winner = ($_m->teamA->id == $_m->team_winner);
                    $_matches[$index]->team_b_winner = ($_m->teamB->id == $_m->team_winner);
                    $_matches[$index]->team_c_winner = !empty($_m->team_c) && ($_m->teamC->id == $_m->team_winner);
                    break;
                case 'draw':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>';
                    break;
                case 'cancelled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;CANCELLED</span>';
                    break;
                default:
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = '';
                    break;
            }
            
            $oldList->push($_m);
        }
        return [
            'matches' => $oldList,
            'pointer' => $ptr += 10
        ];
    }
    //show more dotamatches
    public function showDotaMatches($ptr){
        $_matches = Match::dota2Matches()->mainMatches()->whereNotIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->offset($ptr)->limit(10)->get()->load('league','teamA', 'teamB', 'teamC');
        $currList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $index => $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teamawin_percentage = number_format($team_a_percentage, 2);
            $_m->teambwin_percentage = number_format($team_b_percentage, 2);
            $_m->teamcwin_percentage = number_format($team_c_percentage, 2);

            // $_matches[$index]->team_a_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamA->id), 2);
            // $_matches[$index]->team_b_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamB->id), 2);
            $_matches[$index]->match_sched = $_m->schedule->diffForHumans();
            unset($_matches[$index]->fee);
            
            switch($_m->status) {
                case 'open':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = '';
                case 'ongoing':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>';
                    break;
                case 'settled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>';
                    $_matches[$index]->team_a_winner = ($_m->teamA->id == $_m->team_winner);
                    $_matches[$index]->team_b_winner = ($_m->teamB->id == $_m->team_winner);
                    $_matches[$index]->team_c_winner = !empty($_m->team_c) && ($_m->teamC->id == $_m->team_winner);
                    break;
                case 'draw':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>';
                    break;
                case 'cancelled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;CANCELLED</span>';
                    break;

                default:
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = '';
                    break;
            }
            
            $oldList->push($_m);
        }
        return [
            'matches' => $oldList,
            'pointer' => $ptr += 10
        ];
    }
    //show more csgo matches
    public function showCsgoMatches($ptr) {
        $_matches = Match::csgoMatches()->mainMatches()->whereNotIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->offset($ptr)->limit(10)->get()->load('league','teamA', 'teamB', 'teamC');
        $currList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $index => $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                

            }
            $_m->teamawin_percentage = number_format($team_a_percentage, 2);
            $_m->teambwin_percentage = number_format($team_b_percentage, 2);
            $_m->teamcwin_percentage = number_format($team_c_percentage, 2);

            // $_matches[$index]->team_a_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamA->id), 2);
            // $_matches[$index]->team_b_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamB->id), 2);
            $_matches[$index]->match_sched = $_m->schedule->diffForHumans();
            unset($_matches[$index]->fee);
            
            switch($_m->status) {
                case 'open':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = '';
                case 'ongoing':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>';
                    break;
                case 'settled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>';
                    $_matches[$index]->team_a_winner = ($_m->teamA->id == $_m->team_winner);
                    $_matches[$index]->team_b_winner = ($_m->teamB->id == $_m->team_winner);
                    $_matches[$index]->team_c_winner = !empty($_m->team_c) &&  ($_m->teamC->id == $_m->team_winner);
                    break;
                case 'draw':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>';
                    break;
                case 'cancelled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;CANCELLED</span>';
                    break;
                default:
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = '';
                    break;
            }
            
            $oldList->push($_m);
        }
        return [
            'matches' => $oldList,
            'pointer' => $ptr += 10
        ];
    }
    //show more sports matches
    public function showSportsMatches($ptr) {
        $_matches = Match::sportsMatches()->mainMatches()->whereNotIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->offset($ptr)->limit(10)->get()->load('league','teamA', 'teamB', 'teamC');
        $currList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $index => $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;
            
            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teamawin_percentage = number_format($team_a_percentage, 2);
            $_m->teambwin_percentage = number_format($team_b_percentage, 2);
            $_m->teamcwin_percentage = number_format($team_c_percentage, 2);

            // $_matches[$index]->team_a_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamA->id), 2);
            // $_matches[$index]->team_b_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamB->id), 2);
            $_matches[$index]->match_sched = $_m->schedule->diffForHumans();
            unset($_matches[$index]->fee);
            
            switch($_m->status) {
                case 'open':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = '';
                case 'ongoing':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>';
                    break;
                case 'settled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>';
                    $_matches[$index]->team_a_winner = ($_m->teamA->id == $_m->team_winner);
                    $_matches[$index]->team_b_winner = ($_m->teamB->id == $_m->team_winner);
                    $_matches[$index]->team_c_winner = !empty($_m->team_c) &&  ($_m->teamC->id == $_m->team_winner);
                    break;
                case 'draw':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>';
                    break;
                case 'cancelled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;CANCELLED</span>';
                    break;    
                default:
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = '';
                    break;
            }
            
            $oldList->push($_m);
        }
        return [
            'matches' => $oldList,
            'pointer' => $ptr += 10
        ];
    }

    //show more lol matches
    public function showLolMatches($ptr) {
        $_matches = Match::LolMatches()->mainMatches()->whereNotIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->offset($ptr)->limit(10)->get()->load('league','teamA', 'teamB', 'teamC');
        $currList = collect();
        $oldList = collect();
        $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($_matches as $index => $_m) {
            $match_details = $query->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 0;

            if($match_details->count()) {
                $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }
            $_m->teamawin_percentage = number_format($team_a_percentage, 2);
            $_m->teambwin_percentage = number_format($team_b_percentage, 2);
            $_m->teamcwin_percentage = number_format($team_c_percentage, 2);
            // $_matches[$index]->team_a_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamA->id), 2);
            // $_matches[$index]->team_b_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamB->id), 2);
            $_matches[$index]->match_sched = $_m->schedule->diffForHumans();
            unset($_matches[$index]->fee);
            
            switch($_m->status) {
                case 'open':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = '';
                case 'ongoing':
                    $_matches[$index]->is_current = true;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>';
                    break;
                case 'settled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>';
                    $_matches[$index]->team_a_winner = ($_m->teamA->id == $_m->team_winner);
                    $_matches[$index]->team_b_winner = ($_m->teamB->id == $_m->team_winner);
                    $_matches[$index]->team_c_winner = !empty($_m->team_c) && ($_m->teamC->id == $_m->team_winner);
                    break;
                case 'draw':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>';
                    break;
                case 'cancelled':
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = $_m->schedule->diffForHumans() .
                            ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;CANCELLED</span>';
                    break;    
                default:
                    $_matches[$index]->is_current = false;
                    $_matches[$index]->status = '';
                    break;
            }
            
            $oldList->push($_m);
        }
        return [
            'matches' => $oldList,
            'pointer' => $ptr += 10
        ];
    }

    //show more playoffs matches
    public function showNbaPlayoffsMatches($ptr) {
            $_matches = Match::scopeNbaPlayoffsMatches()->mainMatches()->whereNotIn('matches.status',['open','ongoing'])->orderBy('schedule', 'desc')->offset($ptr)->limit(10)->get()->load('league','teamA', 'teamB', 'teamC');
            $currList = collect();
            $oldList = collect();
            $query = \DB::table('bets')->whereIn('match_id', $_matches->pluck('id'))
                        ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                        ->groupBy('match_id', 'team_id')->get();
            foreach ($_matches as $index => $_m) {
                $match_details = $query->where('match_id', $_m->id);
                $team_a_percentage = 50;
                $team_b_percentage = 50;
                $team_c_percentage = 0;
                if($match_details->count()) {
                    $total_bets = $query->where('match_id', $_m->id)->sum('total_bets');
                    $team_a_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                    $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;
    
                    $team_b_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                    $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                    $team_c_bets = $query->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                    $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                    
                }

                $_m->teamawin_percentage = number_format($team_a_percentage, 2);
                $_m->teambwin_percentage = number_format($team_b_percentage, 2);
                $_m->teamcwin_percentage = number_format($team_c_percentage, 2);
                // $_matches[$index]->team_a_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamA->id), 2);
                // $_matches[$index]->team_b_winPercentage = number_format(matchWinPercentagePerTeam($_m->id, $_m->teamB->id), 2);
                $_matches[$index]->match_sched = $_m->schedule->diffForHumans();
                unset($_matches[$index]->fee);
                
                switch($_m->status) {
                    case 'open':
                        $_matches[$index]->is_current = true;
                        $_matches[$index]->status = '';
                    case 'ongoing':
                        $_matches[$index]->is_current = true;
                        $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                                ' <span style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px">&nbsp;LIVE</span>';
                        break;
                    case 'settled':
                        $_matches[$index]->is_current = false;
                        $_matches[$index]->status = $_m->schedule->diffForHumans() . 
                                ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;SETTLED</span>';
                        $_matches[$index]->team_a_winner = ($_m->teamA->id == $_m->team_winner);
                        $_matches[$index]->team_b_winner = ($_m->teamB->id == $_m->team_winner);
                        $_matches[$index]->team_c_winner = !empty($_m->team_c) && ($_m->teamC->id == $_m->team_winner);
                        break;
                    case 'draw':
                        $_matches[$index]->is_current = false;
                        $_matches[$index]->status = $_m->schedule->diffForHumans() .
                                ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;DRAW - CREDITS RETURNED</span>';
                        break;
                    case 'cancelled':
                        $_matches[$index]->is_current = false;
                        $_matches[$index]->status = $_m->schedule->diffForHumans() .
                                ' <span style="color: #606060; font-weight: bold; font-size: 16px">&nbsp;CANCELLED</span>';
                        break;    
                    default:
                        $_matches[$index]->is_current = false;
                        $_matches[$index]->status = '';
                        break;
                }
                
                $oldList->push($_m);
            }
            return [
                'matches' => $oldList,
                'pointer' => $ptr += 10
            ];
        }
    public function profile()
    {

        $cacheKey = getUserCacheKey();

        $betaBadgeCacheKey = 'betaBadge_' . $cacheKey;
        $beta_badge = Cache::remember( $betaBadgeCacheKey, 120 , function (){
    
            return \App\Badge::where('name','like','betatester')->first();
        
        });
    
        $earningsCacheKey = 'userEarnings_' . $cacheKey;

        $earnings = Cache::remember( $earningsCacheKey, 5 , function (){
            
            $temp = [];
            $temp['today'] = number_format(\Auth::user()->bets()->earnings('today')->first()->total,2);
            $temp['weekly'] = number_format(\Auth::user()->bets()->earnings('weekly')->first()->total,2);
            $temp['monthly'] = number_format(\Auth::user()->bets()->earnings('monthly')->first()->total,2);
            $temp['annual'] = number_format(\Auth::user()->bets()->earnings('annual')->first()->total,2);
            $temp['total'] = number_format(\Auth::user()->bets()->earnings('total')->first()->total,2);
            $temp['total_commissions'] = number_format(\Auth::user()->totalCommission(),2);
            $temp['last_updated'] = "Last updated at " . date('Y-m-d H:i:s');

            return $temp;
    
        });

        $userBadgesCacheKey = 'userBadges_' . $cacheKey;
        $user_badges = Cache::remember( $userBadgesCacheKey, 120 , function (){
    
            return \Auth::user()->badges;
        
        });

        $hasBadgePartner = $user_badges->where('name','PARTNER')->count();

        $isAgent = \Auth::user()->isAgent();

        $totalActiveWallet = \Auth::user()->totalActiveWallet();

        $partner = \Auth::user()->userPartner;
        $provinces = ( !empty($partner) || !empty($hasBadgePartner) ) ? \App\Province::all() : null;


        $settings = [
            'bdo-account-name' => '',
            'bdo-account-number' => '',
            'bpi-account-name' => '',
            'bpi-account-number' => '',
            'metro-account-name' => '',
            'metro-account-number' => '',
            'remittance-name' => '',
            'remittance-number' => '',
            'remittance-location' => '',
            'coins-wallet-address' => '',
            'security-account-name' => '',
            'security-account-number' => ''
        ];
        foreach(\App\SiteSetting::where('status', 1)->get() as $siteSettings) {
            if(isset($settings[$siteSettings->name])) {
                $settings[$siteSettings->name] = $siteSettings->value;
            }
        }
        
        return view('profile', compact('beta_badge', 'partner', 'provinces','earnings','settings', 'user_badges', 'totalActiveWallet', 'isAgent', 'hasBadgePartner'));
    }
    
    public function requestBetaTester(Request $request)
    {
        $user = \App\User::find($request->user_id);
        $badge = \App\Badge::where('name', 'like', 'betatester')->first();
        if ($badge) {
            if(!$user->badges->contains($badge->id)){
                $user->badges()->attach($badge->id);
                if ($user->rewards->where('type', 'badge')->where('class_id', $badge->id)->count() == 0 && $badge->credits > 0) {
                    $reward = \App\Reward::create([
                                'user_id' => $user->id,
                                'type' => 'badge',
                                'class_id' => $badge->id,
                                'description' => 'Awarded Badge: ' . $badge->name . ($badge->credits ? ' with ' . $badge->credits . ' credits' : ''),
                                'credits' => $badge->credits
                    ]);
                    $user->credits += $badge->credits;
                    $user->save();
                }
                return ['success' => true];
            }else{
                return ['success' => false];
            }
        } else
            return ['success' => false];
    }
    
    public function impersonate($uid) {
        $user = \App\User::find($uid);
        $isAllowed = in_array(Auth()->id(), getSuperAdminIds());

        if ($user) {
            if ($isAllowed) {
                \Auth::login($user);
                return redirect('/login');
            } else {
                return redirect('/');
            }
        } else
            return abort(500, 'Invalid username entered!');
    }

    /**
     * Get market item 
     * Display Item on market view
     *
     */
    public function displayItemMarket()
    {
        $upload = Product::all();
        return view('market', compact('upload'));
    }

    public function processClaimGiftCode(Request $request)
    {
        $requestCode = !empty($request->input('code')) ? $request->input('code') : '';
        $user = \Auth::user();
        $giftCodesUsedToday = $user->giftCodes()->usedToday()->first();
        $attemptsToday = $user->giftCodesAttempts()->today()->first();
        $attemptsTodayCount = !empty( $attemptsToday ) ? $attemptsToday->count : 0;
        $totalAttempts =  $user->giftCodesAttempts()->sum('count');

        $where = [
            'code' => $requestCode
        ];

        $code = \App\GiftCode::where($where)->first();

        //check if code is already in use
        if($code && $code->status == 1) {
            return json_encode([
                'success' => false,
                'message' => 'Entered gift code already in use.'
            ]);
        }

        if( !empty($giftCodesUsedToday) && !empty($code) && $code->purpose == 1){ //a user can only claim one gift code per day

            return json_encode([
                'success' => false,
                'message' => 'Sorry, you can only claim one (1) Gift Code per day.'   
            ]);

        }else if( !empty($code) && $code->purpose == 3){ //if disbalance fix

            $code->status = 1;
            $code->user_id = $user->id;
            $code->date_redeemed = date('Y-m-d H:i:s');
            $code->save();

            return json_encode([
                'success' => true,
                'message' => 'Successfully used ' . $requestCode . ' for fixing disbalance.'     
            ]);

        }else if($totalAttempts >= 10){ //if failed attempts overall is over 10, blocked the user indefinitely, until we unblock his/her account

            $bannedUntil = Carbon::now()->addYears(10);
            $user->banned_until = $bannedUntil;
            $user->save();

            return json_encode([
                'success' => false,
                'message' => 'Account Blocked because of too many failed attempts. Please contact 2ez.bet Customer Service <a href="https://www.facebook.com/2ez.bet/" target="_blank">here.</a>'   
            ]);

        }else if($attemptsTodayCount >= 3){ //if failed attemps already passed 3 times, account will be blocked for 24 hours

            $bannedUntil = Carbon::now()->addDay();
            $user->banned_until = $bannedUntil;
            $user->save();

            return json_encode([
                'success' => false,
                'message' => 'Account locked for 24 hours because of too many failed attempts. Please contact 2ez.bet Customer Service <a href="https://www.facebook.com/2ez.bet/" target="_blank">here.</a>'   
            ]);

        }else{

        }

        if(!empty($requestCode)){
   
            if(!empty($code)){

                if($user->credits == null){
                    $user->credits = $code->amount;
                    $user->save();
                }else{
                    $user->increment('credits',$code->amount);
                }
                
                $code->status = 1;
                $code->user_id = $user->id;
                $code->date_redeemed = date('Y-m-d H:i:s');
                $code->save();
                return json_encode([
                    'success' => true,
                    'message' => 'Successfully claimed ' . $code->amount . ' 2ez.bet credits. Click <a href="/home">here</a> to start betting.'    
                ]);

                
            }else{

                if(!empty($attemptsToday)){
                    $attemptsToday->increment('count');
                }else{

                    \App\GiftCodesAttempt::create([
                        'user_id' => $user->id,
                        'count' => 1
                    ]);
                }

                return json_encode([
                    'success' => false,
                    'message' => 'Gift code '.$requestCode.' not found.'    
                ]);
            }               
            

        }else{
            return json_encode([
                'success' => false,
                'message' => 'Gift code cannot be empty.'    
            ]);
        }
        
    }

    public function cacheFlush(){
        if( Auth()->user()->isAdmin()){
            Cache::flush();
            return 1;
        }
    }

    public function resetPassword(Request $request){
        $user = Auth()->user();
        $validator = \Validator::make($request->all(), [
            'password' => 'required',
        ]);
        
        if ($validator->passes()) {
            $user->password = \Hash::make($request->password);
            $user->save();
            return ['success' => true, 'message' => 'Password successfully changed.'];
        }else{
            return [ 'success' => false, 'errors' => $validator->errors()];
        }
    }

    /**
     * this function will check if user has pending transaction 
     */
    public function fetchPendingDeposits() {
        if(\Auth::user()) {
            $result = \App\Transaction::where("status", "processing")
                ->where("type", "deposit")
                ->where("status", "processing")
                ->where(function($query) {
                    $query->where('picture', '');
                    $query->orWhereNull('picture');
                })
                ->where('user_id', \Auth::user()->id)
                ->first();

            if(empty($result)) {
                $result = \App\PartnerTransaction::where("status", "processing")
                ->where("type", "deposit")
                ->where("status", "processing")
                ->where(function($query) {
                    $query->where('picture', '');
                    $query->orWhereNull('picture');
                })
                ->where('user_id', \Auth::user()->id)
                ->with('partner')
                ->first();

                if($result) {
                    $result->deposit_type = 'partner';
                }
            } else {
                $result->deposit_type = 'direct';
            }

            return $result;
        }
    }

    /**
     * This function will use affliate link
     * redirect to register if not logged in
     * redirect to purchase with voucher code if logged in
     */
    public function userAffliation($voucher_code = "") {
        $user = \Auth::user();

        if(empty($user)) {
            return redirect('/register?vcode=' . $voucher_code);
        } else {
            $warning = "";

            //check if user voucher and affliate voucher is a match
            if(!empty($user->redeem_voucher_code) && $voucher_code <> $user->redeem_voucher_code) {
                $warning = "Voucher Code mismatch. The original voucher code will be used.";
            }

            //check if user has voucher and add this warning if voucher not exist for the user
            if(empty($user->redeem_voucher_code)) {
                $warning = "Voucher Code can only be redeemed during registration";
            }
            
            session(['affliate_voucher_code_warning' => $warning]);

            return redirect('/purchase');
        }
    }

    /** 
     * This function will deactivate a userAccount
     */
    public function deactivateAccount() {
        $user         = \Auth::user();
        $user->status = 'deactivated';

        try {
            $user->save();
        } catch(Exception $e) {
            return [
                'success' => false,
                'message'  => 'Failed to deactivate account. Try again later'
            ];
        }

        return [
            'success' => true,
            'message'  => 'Account successfully deactivated.'
        ];
    }

    /** 
     * This function will deactivate a userAccount
     */
    public function reactivateAccount(Request $request) {
        if($request->user_id) {
            $user         = \App\User::find($request->user_id);
            $user->status = 'active';

            $save = $user->save();

            session([
                'message' => 'Account successfully reactivated. Please login again.'
            ]);

            return [ 
                'success' => $save
            ];
        }

        return [ 
            'success' => false,
            'errors'  => 'User Not Found.'
        ];
    }
}
