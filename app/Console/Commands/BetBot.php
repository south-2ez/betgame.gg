<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BetbotLessAdjustments;
use App\Repositories\MatchRepository;
use App\Repositories\BetRepository;
use App\Repositories\BetHistoryRepository;
use App\Repositories\BetbotAdjustmentsRepository;

use Carbon;

class BetBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betbot:manipulate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Betbot adjusting bets of bots...';

    protected $matchRepository;
    protected $betRepository;
    protected $betHistoryRepository;
    protected $bbAdjustmentRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->matchRepository = new MatchRepository;
        $this->betRepository = new BetRepository;
        $this->betHistoryRepository = new BetHistoryRepository;
        $this->bbAdjustmentRepository = new BetbotAdjustmentsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //get total bet of match x 
        //get total amount to deduct x 
        //get ratio of both team  x
        //get amount to deduct on both teams based on ratio x
        //get bet bots of each team x
        //sort from low to high x
        //count number of betbots of each team x
        //divide the bet based on the betbots count x
        //deduct the amount on each bet x 
        //if deduct > betbot amount x
        //add to the next one to deduct x

        $time1 = microtime(true);
        
        //get open matches
        $inSixtyMinutes = Carbon::now()->addMinutes(60);
        $matches = $this->matchRepository->getMatchesWhere([
            ['status', 'open'],
            ['is_initial_odds_enabled', 1],
            ['schedule', '<=', $inSixtyMinutes->format('Y-m-d H:i:s')],
            ['schedule', '>=', Carbon::now()->format('Y-m-d H:i:s')]
        ]);

        if(!empty($matches)){

           
            foreach($matches as $matchKey => $match){
                
                $minAdjustment = $this->bbAdjustmentRepository->getAdjustmentMark($match->schedule);
                
                $where = [
                    'match_id' => $match->id,
                    // 'match_schedule' => $match->schedule,
                    'adjusted_at' => $minAdjustment
                ];

                $hasDoneAdjustment = $this->bbAdjustmentRepository->hasDoneAdjustment($where);

                if(!$hasDoneAdjustment){
                    //bets of both team
                    $teamABets = $this->betRepository->getTeamBetsByMatchId($match->id, $match->team_a);
                    $teamBBets = $this->betRepository->getTeamBetsByMatchId($match->id, $match->team_b);   
                    $teamABetsSum =  $teamABets->sum('amount');
                    $teamBBetsSum =  $teamBBets->sum('amount');

                    $totalBets =  $teamABetsSum + $teamBBetsSum;
                    if($totalBets < 1 || $teamABetsSum  < 1 || $teamBBetsSum < 1) continue;

                    $percentToDeduct = $this->matchRepository->getAmountToLess($minAdjustment); //get percent of total bet
                    $toDeductAmount = $totalBets * $percentToDeduct;


                   
                    $teamABetBots = $teamABets->where('bot_bet_against_bet_id','>',0)->all();
                    $teamBBetBots = $teamBBets->where('bot_bet_against_bet_id','>',0)->all();
                    
                    $teamABetsSum = array_sum( array_column($teamABetBots, 'amount') );
                    $teamBBetsSum = array_sum( array_column($teamBBetBots, 'amount') );
                    $betBotTotalSum = $teamABetsSum + $teamBBetsSum;

                    $teamABotRatio = $teamABetsSum > 0 ? $teamABetsSum / $betBotTotalSum : 0;
                    $teamBBotRatio = $teamBBetsSum > 0 ? $teamBBetsSum / $betBotTotalSum : 0;        
                    
                    $teamADeductTotal = $toDeductAmount * $teamABotRatio;
                    $teamBDeductTotal = $toDeductAmount * $teamBBotRatio;        
                
                    $teamABetBotsCount = count($teamABetBots);
                    $teamBBetBotsCount = count($teamBBetBots);

                    if( $teamABetBotsCount > 0 || $teamBBetBotsCount > 0 ){

                        echo "Processing Match: " . $match->id ." Schedule: " .$match->schedule . " - total bets: " . $totalBets. " - " . $toDeductAmount ."\t";
                        // echo "\n";

                        $teamADeduct = $teamABetBotsCount > 0 ? $teamADeductTotal / $teamABetBotsCount : 0;
                        $teamBDeduct = $teamBBetBotsCount > 0 ? $teamBDeductTotal / $teamBBetBotsCount : 0;

                        //iterate through betbots of team a
                        if(!empty($teamABetBots)){
                            $useAmount = $teamADeduct;
                            echo " Team A: " . $teamABotRatio . " - " . $teamADeduct .  " (" .$teamABetBotsCount. ") " ;
                            echo "\t";

                            foreach($teamABetBots as $bet){
                                $updateAmount = $bet->amount - $useAmount;
                                if($updateAmount > 0){
                                    // $bet->decrement('amount', $useAmount);
                                    // $bet->user->increment('credits', $useAmount);
                                    // $bet->amount = $updateAmount;
                                    // $bet->save();


                                    $lessAdjustments = BetbotLessAdjustments::firstOrNew(
                                        [ 'bet_id' => $bet->id ],[]
                                    );

                                    $lessAdjustments->amount += $useAmount;
                                    $lessAdjustments->save();
                                    
                                    $useAmount = $teamADeduct;
                                }else if($updateAmount == 0){
                                    //$bet->delete(); //delete since its already 0
                                    $useAmount = $teamADeduct;
                                }else{
                                    $useAmount += $teamADeduct;
                                }
                            }
                        }
                        
                        //iterate through betbots of team b
                        if(!empty($teamBBetBots)){

                            echo " Team B: " . $teamBBotRatio . " - " . $teamBDeduct .  " (" .$teamBBetBotsCount. ") " ;
                            echo "\t";

                            $useAmount = $teamBDeduct;
                            foreach($teamBBetBots as $bet){
                                $updateAmount = $bet->amount - $useAmount;
                                if($updateAmount > 0){
                                    
                                    // $bet->amount = $updateAmount;
                                    // $bet->save();
                                    // $bet->decrement('amount', $useAmount);
                                    // $bet->user->increment('credits', $useAmount);
                                    // $useAmount = $teamBDeduct;

                                    $lessAdjustments = BetbotLessAdjustments::firstOrNew(
                                        [ 'bet_id' => $bet->id ],[]
                                    );

                                    $lessAdjustments->amount += $useAmount;
                                    $lessAdjustments->save();  

                                }else if($updateAmount == 0){
                                    //$bet->delete(); //delete since its already 0
                                    $useAmount = $teamBDeduct;
                                }else{
                                    $useAmount += $teamBDeduct;
                                }
                            }
                        }

                        // $match->bot_bet_adjusted_times += 1;
                        $match->save();
                         \App\UpdatedMatchOddsLogs::create([
                             'match_id' => $match->id,
                             'type' => 'betbot_adjustments',
                             'message' => "Betbot adjustment at Minute mark: {$minAdjustment}, adjust amount: {$toDeductAmount}"
                         ]);

                        // echo "Adjusted times: " . $match->bot_bet_adjusted_times;
                        echo "\n";
                    }

                    
                    $this->bbAdjustmentRepository->create([
                        'match_id' => $match->id,
                        'match_schedule' => $match->schedule,
                        'adjusted_bet_ids' => json_encode(array_merge($teamABets->pluck('id')->toArray(), $teamBBets->pluck('id')->toArray())),
                        'adjusted_amount' => $toDeductAmount,
                        'adjusted_at' => $minAdjustment
                    ]);

                }else{
                    echo "Match " . $match->id . " already adjusted for ". $minAdjustment . " mins mark \n";
                }
            }
            

        }else{
            echo "No open matches...";
        }
        
        $time2 = microtime(true);
        echo "\n";
        echo 'Execution time:  ' . ($time2 - $time1) . " seconds \n"; //value in seconds
    }
}
