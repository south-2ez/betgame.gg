<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Repositories\BetRepository;
use App\Match;

class SetMatchBetsRatio implements ShouldQueue
{   
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    protected $data;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
       
        $match = $this->data['match'];
        //get all user ids of admin and matchmanager
        $adminMMUsersIds = getAdminMatchManagersUserIds();   
        $match_ids = []; //match ids that we need to remove admin bets
        $match_ids[] = $match->id;

        $subMatches = $match->type == 'main' ? 
                            $match->subMatches->where('game_grp', '<=', 1) : 
                            (
                                $match->sub_type == 'main' ?  
                                    \App\SubMatch::where('main_match', $match->main_match)
                                        ->where('game_grp', $match->game_grp)
                                        ->get() : 
                                    NULL
                            );
        
                            
        $subMatchesIds = !empty($subMatches) ? $subMatches->pluck('id') : [];
        // $match_ids = [$match->id, ...$subMatchesIds];
        $match_ids = array_merge($match_ids,$subMatchesIds->toArray());
        
        //proceed delete admin bets
        $adminBetsDelete =  \App\Bet::whereIn('user_id',$adminMMUsersIds)->whereIn('match_id', $match_ids)->delete();

        $matchBets = \App\Bet::where('match_id', $match->id)->get();
        $matchRatio = calculateMatchTeamRatios($match, $matchBets);  
        
        $match->team_a_ratio = $matchRatio['team_a_ratio'];
        $match->team_b_ratio = $matchRatio['team_b_ratio'];
        $match->team_c_ratio = $matchRatio['team_c_ratio'];
        $match->save();

        Log::info('QUEUE: Match ID: ' . $match->id . ' | team_a : ' . $matchRatio['team_a_ratio'] .  ' | team_b : ' . $matchRatio['team_b_ratio'] .  ' | team_c : ' . $matchRatio['team_c_ratio']);

        $updateMatchBetsRatio = \DB::statement("
                                    update bets set ratio = 
                                        CASE
                                            WHEN team_id = '". $match->team_a. "'
                                            THEN ". $matchRatio['team_a_ratio'] ."
                                            WHEN team_id = '". $match->team_b. "'
                                            THEN ". $matchRatio['team_b_ratio'] ."                                            
                                            ELSE ". $matchRatio['team_c_ratio'] ."
                                        END
                                    WHERE match_id = '". $match->id. "'
                                ");

        if(!empty($subMatchesIds)){
            foreach($subMatches as $key => $subMatch){
                $subMatchBets = \App\Bet::where('match_id', $subMatch->id)->get();
                $subMatchRatio = calculateMatchTeamRatios($subMatch, $subMatchBets);
                $subMatch->team_a_ratio = $subMatchRatio['team_a_ratio'];
                $subMatch->team_b_ratio = $subMatchRatio['team_b_ratio'];
                $subMatch->team_c_ratio = $subMatchRatio['team_c_ratio'];
                $subMatch->save();

                $updateSubMatchBetsRatio = \DB::statement("
                                            update bets set ratio = 
                                                CASE
                                                    WHEN team_id = '". $subMatch->team_a. "'
                                                    THEN ". $subMatchRatio['team_a_ratio'] ."
                                                    WHEN team_id = '". $subMatch->team_b. "'
                                                    THEN ". $subMatchRatio['team_b_ratio'] ."                                                    
                                                    ELSE ". $subMatchRatio['team_c_ratio'] ."
                                                END
                                            WHERE match_id = '". $subMatch->id. "'
                                        ");   
                                        
                Log::info('QUEUE: Match ID: ' . $subMatch->id . ' | team_a : ' . $subMatchRatio['team_a_ratio'] .  ' | team_b : ' . $subMatchRatio['team_b_ratio'] .  ' | team_c : ' . $subMatchRatio['team_c_ratio']);                        
            }
        }
    }
}
