<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cache;
use \App\League;
use \App\Match;

class SetAndCacheTeamRatios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hermes:set-cache-team-ratios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for updating teams ratios for both tournaments & matches then caching it.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
         * Tournaments setting cachce ratios 
         */

        $leagues = League::active()->with('champion')->orderby('leagues.status','desc')->get();
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

        $leagueTeamsRatios = [];

        foreach ($leagues as $_l) {

            $leagueId =  $_l->id;
            $total_bets = $leagueBets->where('league_id', $leagueId)->sum('amount');

            $leagueTeamsCacheKey = "league-teams-{$leagueId}";


            $leagueTeams =  Cache::remember( md5($leagueTeamsCacheKey), 60 , function () use ($_l) {
                return $_l->teams()->get();
    
            });
                    
            $_l->teams = $leagueTeams;

            $teamRatios = [];

            foreach($leagueTeams as $tid => $team) {
                $team_bets = $leagueBets->where('league_id', $_l->id)->where('team_id', $team->id)->sum('amount');

                $temp = new \stdClass();
                $temp->teamPercentage = $total_bets ? ($team_bets / $total_bets) * 100 : 0;
                $temp->teamRatio =  $team_bets ? $total_bets / $team_bets * (1 - $_l->betting_fee) : 0;
                $teamRatios[$team->id] = $temp;
            }

            $leagueTeamsRatios[$leagueId] = $teamRatios;

        }

        /**
         * Match setting cache ratio
         */
         
        $matches = Match::mainMatches()->with('teamA','teamB','league')->whereIn('matches.status',['open','ongoing']);
        
        $_matches = $matches->get();
        
        $openMatches = $_matches->where('status','open');
        $liveMatches = $_matches->where('status','ongoing');

        $openMatches_ids = $openMatches->pluck('id')->sort();
        $liveMatches_ids = $liveMatches->pluck('id')->sort();
        $match_updated_ats = $_matches->pluck('updated_at');

        $liveMatchesCacheKeys = "live-matches-{$liveMatches_ids->implode('_')}-{$match_updated_ats->implode('-')}";


        $liveMatchesBets = Cache::remember( md5($liveMatchesCacheKeys), 60 , function () use ($liveMatches_ids) {

            return \App\Bet::selectRaw("SUM(amount) AS total_bets, team_id, match_id")
                                ->whereIn('match_id', $liveMatches_ids)
                                ->groupBy('match_id', 'team_id')->get();
        });

        $liveList = collect();
        $openList = collect();
        $oldList = collect();
        $maxTotal = $matches->count();


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
    
        $matchTeamRatios = [];
                    
        foreach ($_matches as $_m) {
            $match_details = $matchBets->where('match_id', $_m->id);
            $team_a_percentage = 50;
            $team_b_percentage = 50;
            $team_c_percentage = 50;

            if($match_details->count()) {
                $total_bets = $matchBets->where('match_id', $_m->id)->sum('total_bets');
                $team_a_bets = $matchBets->where('match_id', $_m->id)->where('team_id', $_m->team_a)->sum('total_bets');
                $team_a_percentage = $total_bets ? ($team_a_bets / $total_bets) * 100 : 0;

                $team_b_bets = $matchBets->where('match_id', $_m->id)->where('team_id', $_m->team_b)->sum('total_bets');
                $team_b_percentage = $total_bets ? ($team_b_bets / $total_bets) * 100 : 0;

                $team_c_bets = $matchBets->where('match_id', $_m->id)->where('team_id', $_m->team_c)->sum('total_bets');
                $team_c_percentage = $total_bets ? ($team_c_bets / $total_bets) * 100 : 0;                
            }

            $temp = new \stdClass();
            $temp->teama_percentage = number_format($team_a_percentage, 2);
            $temp->teamb_percentage = number_format($team_b_percentage, 2);
            $temp->teamc_percentage = number_format($team_c_percentage, 2);

            $matchTeamRatios[$_m->id] = $temp;
        }

        $leagueTeamsRatiosCacheKey = md5('for-home-page-active-leagues-ratio');
        $matchTeamRatiosCacheKey = md5('for-home-page-active-matches-ratio');

        Cache::put($leagueTeamsRatiosCacheKey, $leagueTeamsRatios, 60);
        Cache::put($matchTeamRatiosCacheKey, $matchTeamRatios, 60);
        
        return true;

    }
}
