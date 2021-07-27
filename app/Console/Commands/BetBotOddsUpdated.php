<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositories\UpdatedMatchOddsLogsRepository;
use App\Repositories\BetRepository;
use App\BetHistory;

class BetBotOddsUpdated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betbot:process-updated-odds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates bet of bots if a match has updated the initial ratio';

    protected $oddsLogsRepository;
    protected $betRepository;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UpdatedMatchOddsLogsRepository $oddsLogsRepository, BetRepository $betRepository)
    {
        $this->oddsLogsRepository = $oddsLogsRepository;
        $this->betRepository = $betRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unprocessedUpdates = $this->oddsLogsRepository->getUnprocessedUpdates();
        if($unprocessedUpdates->isNotEmpty()){

            
            echo "BETID:\t USER:\t  AMOUNT\n"; 

            foreach($unprocessedUpdates as $update){

                $totalBets = 0;
                $totalABets = 0;
                $totalBBets = 0;
                $match = $update->match;
                $bets = $update->match->bets->sortBy('created_at');
                $processedBets = [];

                foreach($bets as $key=> $bet){
                    echo "{$bet->id}\t: {$bet->user_id}\t  {$bet->amount} *\n"; 

                    $isTeamA = $bet->team_id == $match->team_a;

                    if($bet->bot_bet_against_bet_id > 0){

                        $againstBet = $processedBets[$bet->bot_bet_against_bet_id];
                        $currentBet = $bet->amount;
                        $bot_bet_amount = $againstBet->amount;

                        $researched_ratio_team_a = ($match->team_a_initial_odd / $match->team_b_initial_odd) * ( 1 - ($match->fee * 2)); //inputted researched value by match manager
                        $researched_ratio_team_b = ($match->team_b_initial_odd / $match->team_a_initial_odd) * ( 1 - ($match->fee * 2)); //inputted research value by match manager      
               
                        if($key == 0){
                            $team_a_ratio = $totalABets ? $totalBets / $totalABets * (1 - $match->fee) : 0;
                            $team_b_ratio = $totalBBets ? $totalBets / $totalBBets * (1 - $match->fee) : 0;
                        }else{
                            $team_a_ratio = $researched_ratio_team_a + 1;
                            $team_b_ratio = $researched_ratio_team_b + 1;                           
                        }
                    
                        // $use_odds = $team_a_ratio <= $team_b_ratio ? $team_a_ratio : $team_b_ratio;
                        // $bot_bet_team = $bet->team_id == $match->team_a ? $match->team_b : $match->team_a; 

                        // if($match->team_a_initial_odd >= 50 && $match->team_a_initial_odd <= 69){
                        //     $bot_bet_amount = $bot_bet_amount * ($use_odds - 1); 
                        // }else{
                            
                        //     if($bot_bet_team == $match->team_a){
                        //         $bot_bet_amount = $bot_bet_amount / ($use_odds - 1); 
                        //     }else{
                        //         $bot_bet_amount = $bot_bet_amount * ($use_odds - 1); 
                        //     }
                        // }


                        // $bot_less_bet = env('BOT_BET_LESS_BY') ? env('BOT_BET_LESS_BY') : 0.5; //how much less we are going to put in bet
                        // $bot_bet_amount = $bot_bet_amount * ( 1 - ($bot_less_bet/100));

                        $use_odds = $team_a_ratio <= $team_b_ratio ? $team_a_ratio : $team_b_ratio;
                        
                        //$bot_bet_amount = $placed_bet; //initially we set the bet of the same as the user bets 
                        $bot_bet_team = $bet->team_id == $match->team_a ? $match->team_b : $match->team_a;  //bot will use the opposing team of the user bet on

                        //if($match->team_a_initial_odd >= 50 && $match->team_a_initial_odd <= 69){
                        //   $bot_bet_amount = $bot_bet_amount * ($use_odds - 1); 
                        // }else{
                        //   if($bot_bet_team == $match->team_a){
                        //     $bot_bet_amount = $bot_bet_amount / ($use_odds - 1); 
                        //   }else{
                        //     $bot_bet_amount = $bot_bet_amount * ($use_odds - 1); 
                        //   }
                        // }

                        $bot_less_bet = env('BOT_BET_LESS_BY') ? env('BOT_BET_LESS_BY') : 0.5; //how much less we are going to put in bet

                        //less our bet with the set less bet amount default is 10%
                        // eductedAmount = deductedAmount * (1 - this.botLessBy / 100);
                        // $bot_bet_amount = $bot_bet_amount * ( 1 - ($bot_less_bet/100));

                        $botBetCalculateDetails = [
                            'placed_bet' => $againstBet->amount,
                            'isBotTeamA' => $bet->team_id == $match->team_a,
                            'use_odds' => $use_odds,
                            'less_bet' => $bot_less_bet,
                            'bet_id' => $bet->id,
                            'researched_ratio_team_a' => $researched_ratio_team_a,
                            'researched_ratio_team_b' => $researched_ratio_team_b,
                        ]; 
                        
                        $bot_bet_amount = $this->betRepository->calculateBotBet($botBetCalculateDetails);
                        
                        $bet->amount = $bot_bet_amount;
                        $bet->save();

                        $bet->user->increment('credits', $currentBet);
                        $bet->user->decrement('credits', $bot_bet_amount);

                        // Add to bet history
                        BetHistory::create([
                            'type' => 'update',
                            'match_id' => $match->id,
                            'bet_id' => $bet->id,
                            'amount' => $bot_bet_amount,
                            'user_id' => $bet->user_id,
                            'curr_credits' => $bet->user->credits
                        ]);
                        
                    }

                    if($isTeamA){
                        $totalABets += $bet->amount;
                    }else{
                        $totalBBets += $bet->amount;
                    }
                    $totalBets += $bet->amount;
                    $processedBets[$bet->id] = $bet;

                    echo "{$bet->id}\t: {$bet->user_id}\t  {$bet->amount} \n"; 
                }

            }

            $update->processed = 1;
            $update->save();
        }

    }
}
