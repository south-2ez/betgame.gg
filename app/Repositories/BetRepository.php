<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Bet;
use App\User;
use App\BetHistory;
use App\BetbotLessAdjustments;
use App\Repositories\MatchRepository;
use App\Repositories\BetHistoryRepository;

class BetRepository {

  public function create(array $data){

    $bet = new Bet;
    $bet->user_id = $data['user_id'];
    $bet->type = $data['type'];
    $bet->team_id = $data['team_id'];
    $bet->amount = $data['amount'];
    $bet->match_id = $data['match_id'];
    $bet->league_id = $data['league_id'];
    $bet->bot_bet_against_bet_id = $data['bet_id'] > 0 ? $data['bet_id'] : 0;

    if($bet->save()){
      return $bet;
    }

  }

  public function getBetsByMatchId($matchId = 0, $fields = '*'){
    $bets = Bet::select($fields)->where('match_id', $matchId)->get();
    return $bets;
  }

  public function getBetBotsByMatchId($matchId = 0){
    $bets = Bet::where('match_id', $matchId)->where('bot_bet_against_bet_id', '>', 0)->get();
    return $bets;
  }

  public function getTotalMatchBet($matchId = 0){
    $total = Bet::where('match_id', $matchId)->sum('amount');
    return $total;
  }

  public function getTeamTotalMatchBet($matchId, $teamId){
    $total = Bet::where([
        'match_id' => $matchId,
        'team_id' => $teamId
      ])->sum('amount');

    return $total;
  }

  public function getTeamBetsByMatchId($matchId, $teamId, $type = ''){
    $where = [
      'match_id' => $matchId,
      'team_id' => $teamId,
    ];
    if(!empty($type)) $where['type'] = $type;
    $bets = Bet::where($where)->get();
    
    return $bets;
  }
  
  public function lowerBetBot($matchId, $amount){
    
    $bets = $this->getBetBotsByMatchId($matchId);
    if(!empty($bets)){
      foreach($bets as $key => $bet){
        $currentBet = $bet->amount;
        $lessBet = $currentBet - $amount;
        $bet->amount = $lessBet;
        $bet->save();
      }
    }

    return false;
  }  

  public function insertBetBot($data){

      $match = $data['match']; //match details
      $match_id = $match->id; //the match that the bettor placed his/her bet
      $bet_id = $data['bet_id']; //bet of user, we are going to use this id incase user cancels his/her bet and also for updating bet
      $placed_bet =  $data['bet_amount']; //amount betted
      $placed_bet_team = $data['teamid']; //the team that the bettoer placed his/her bet on

      $bot_next_door_user_id = $placed_bet_team == $match->team_a ? env('SERENA_NEXT_DOOR_ID') : env('TOMBOY_NEXT_DOOR_ID');
      // if(empty($bot_next_door_user_id) || empty($match->is_initial_odds_enabled)){
      //     return;
      // }
      $shouldProceed = $this->_shouldBotProceed($bot_next_door_user_id, $match);
      if(!$shouldProceed){
        return;
      }
      
      $researched_ratio_team_a = ($match->team_a_initial_odd / $match->team_b_initial_odd) * ( 1 - ($match->fee * 2)); //inputted researched value by match manager
      $researched_ratio_team_b = ($match->team_b_initial_odd / $match->team_a_initial_odd) * ( 1 - ($match->fee * 2)); //inputted research value by match manager      
      $placed_bet_team_ratio = $placed_bet_team == $match->team_a ? $researched_ratio_team_a : $researched_ratio_team_b;
      $betCount = $match->bets->where('bot_bet_against_bet_id', 0)->count();
     
      $team_a_bets  = 0;
      $team_b_bets = 0;

      if($betCount > 1){

        $team_a_bets = $this->getTeamBetsByMatchId($match->id, $match->team_a)->where('id','!=', $bet_id)->sum('amount');
        $team_b_bets = $this->getTeamBetsByMatchId($match->id, $match->team_b)->where('id','!=', $bet_id)->sum('amount'); 
        $total_bets = $team_a_bets +  $team_b_bets;

        $team_a_ratio = !empty($team_a_bets) ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;
        $team_b_ratio = !empty($team_b_bets) ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;
      }else{
        $team_a_ratio = $researched_ratio_team_a + 1;
        $team_b_ratio = $researched_ratio_team_b + 1;     
      }
      
      // Log::info("Bet count: " . $betCount . " a ratio: " . $team_a_ratio . " b ratio: ". $team_b_ratio. " abets: ". $team_a_bets . " bbets:" . $team_b_bets);
      
      $use_odds = $team_a_ratio <= $team_b_ratio ? $team_a_ratio : $team_b_ratio;
    
      //$bot_bet_amount = $placed_bet; //initially we set the bet of the same as the user bets 
      $bot_bet_team = $placed_bet_team == $match->team_a ? $match->team_b : $match->team_a; //bot will use the opposing team of the user bet on

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
        'placed_bet' => $placed_bet,
        'researched_ratio_team_a' => $researched_ratio_team_a,
        'researched_ratio_team_b' => $researched_ratio_team_b,
        'isBotTeamA' => $placed_bet_team != $match->team_a,
        'use_odds' => $use_odds,
        'less_bet' => $bot_less_bet,
      ]; 

      $bot_bet_amount = $this->calculateBotBet($botBetCalculateDetails);


      //get Bot Next Door details
      $bot_next_door_user = User::find($bot_next_door_user_id);
      
      $bet_data = [
          'user_id' => $bot_next_door_user_id,
          'type' => $match->type,
          'team_id' => $bot_bet_team,
          'amount' => $bot_bet_amount,
          'match_id' => $match_id,
          'league_id' => $match->league_id,
          'bet_id' => $bet_id
      ];

      $newbet = $this->create($bet_data);
      
      //deduct bet amount in bot credits
      // $bot_next_door_user->credits -= $bot_bet_amount;
      // $bot_next_door_user->save();
      $bot_next_door_user->decrement('credits', $bot_bet_amount);
  }

  public function updateBetBot($data){

      $match = $data['match']; //match details
      $match_id = $match->id; //the match that the bettor placed his/her bet
      $bet_id = $data['bet_id']; //bet of user, we are going to use this id incase user cancels his/her bet and also for updating bet
      $placed_bet =  $data['bet_amount']; //amount betted
      $placed_bet_team = $data['teamid']; //the team that the bettoer placed his/her bet on

      $bot_next_door_user_id = $placed_bet_team == $match->team_a ? env('SERENA_NEXT_DOOR_ID') : env('TOMBOY_NEXT_DOOR_ID');
      $shouldProceed = $this->_shouldBotProceed($bot_next_door_user_id, $match);
      // if(empty($bot_next_door_user_id) || empty($match->is_initial_odds_enabled)){
      //     return;
      // }
      if(!$shouldProceed){
        return;
      }

      $researched_ratio_team_a = ($match->team_a_initial_odd / $match->team_b_initial_odd) * ( 1 - ($match->fee * 2)); //inputted researched value by match manager
      $researched_ratio_team_b = ($match->team_b_initial_odd / $match->team_a_initial_odd) * ( 1 - ($match->fee * 2)); //inputted research value by match manager      
      $placed_bet_team_ratio = $placed_bet_team == $match->team_a ? $researched_ratio_team_a : $researched_ratio_team_b;
      $betCount = $match->bets->where('bot_bet_against_bet_id', 0)->count();
     
      $team_a_bets  = 0;
      $team_b_bets = 0;
      
      if($betCount > 1){

        $team_a_bets = $this->getTeamBetsByMatchId($match->id, $match->team_a)->sum('amount');
        $team_b_bets = $this->getTeamBetsByMatchId($match->id, $match->team_b)->sum('amount'); 
        $total_bets = $team_a_bets +  $team_b_bets;

        $team_a_ratio = $team_a_bets ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;
        $team_b_ratio = $team_b_bets ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;
      }else{
        $team_a_ratio = $researched_ratio_team_a + 1;
        $team_b_ratio = $researched_ratio_team_b + 1;     
      }

      $use_odds = $team_a_ratio <= $team_b_ratio ? $team_a_ratio : $team_b_ratio;
    
      //$bot_bet_amount = $placed_bet; //initially we set the bet of the same as the user bets 
      $bot_bet_team = $placed_bet_team == $match->team_a ? $match->team_b : $match->team_a; //bot will use the opposing team of the user bet on

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
        'placed_bet' => $placed_bet,
        'researched_ratio_team_a' => $researched_ratio_team_a,
        'researched_ratio_team_b' => $researched_ratio_team_b,
        'isBotTeamA' => $placed_bet_team != $match->team_a,
        'use_odds' => $use_odds,
        'less_bet' => $bot_less_bet,
      ]; 

      $bot_bet_amount = $this->calculateBotBet($botBetCalculateDetails);

     

      //get Bot Next Door details
      $bot_next_door_user = User::find($bot_next_door_user_id);
      
      $where = [
          'bot_bet_against_bet_id' => $bet_id,
          'user_id' => $bot_next_door_user_id
      ];
      
      $updatebet = Bet::where($where)->first();

      if(!empty($updatebet)){
          // $updatebet->amount += $bot_bet_amount;
          // $updatebet->save();
          $updatebet->increment('amount', $bot_bet_amount);

          // Add to bet history
          BetHistory::create([
              'type' => 'update',
              'match_id' => $match_id,
              'bet_id' => $bet_id,
              'amount' => $bot_bet_amount,
              'user_id' => $bot_next_door_user_id,
              'curr_credits' => $bot_next_door_user->credits
          ]);
          //deduct bet amount in bot credits
          // $bot_next_door_user->credits -= $bot_bet_amount;
          // $bot_next_door_user->save();
          $bot_next_door_user->decrement('credits', $bot_bet_amount);
      }

      //   Log::info('Test betting bot: ', [ $data, $newbet->toArray() ]);

  }

  public function calculateBotBet($data){

    $placed_bet = $data['placed_bet'];
    $isBotTeamA = $data['isBotTeamA'];
    $use_odds = $data['use_odds'];
    $less_bet = $data['less_bet'];
    $researched_ratio_team_a = $data['researched_ratio_team_a'];
    $researched_ratio_team_b = $data['researched_ratio_team_b'];
    $isBotHigherOdds = ($isBotTeamA) ? ($researched_ratio_team_a > $researched_ratio_team_b) : ($researched_ratio_team_b > $researched_ratio_team_a);
    $bet_id = !empty($data['bet_id']) ? $data['bet_id'] : 0;
    
    $bot_bet_amount = $placed_bet;
    // $bot_bet_amount = $isBotTeamA ?  $bot_bet_amount / ($use_odds - 1) : $bot_bet_amount * ($use_odds - 1);
    if(!$isBotHigherOdds){
      $bot_bet_amount = $bot_bet_amount * ($use_odds - 1);
    }else{
      $bot_bet_amount = $bot_bet_amount / ($use_odds - 1);
    }


    $beforeless = $bot_bet_amount;

    $bot_less_bet = env('BOT_BET_LESS_BY') ? env('BOT_BET_LESS_BY') : 0.5; //how much less we are going to put in bet
    $bot_bet_amount = $bot_bet_amount * ( 1 - ($bot_less_bet/100));

    if(!empty($bet_id)){
      $betLessAdjustment = BetbotLessAdjustments::where('bet_id',$bet_id)->first();
     
      if(!empty($betLessAdjustment->amount)){
         
        $bot_bet_amount -= $betLessAdjustment->amount;

      }
    }
    $bot_bet_amount  = $bot_bet_amount > 0 ? $bot_bet_amount : 1;
    
    // dd($data, $isBotHigherOdds, $beforeless, $bot_bet_amount);
    return $bot_bet_amount;
  }

  private function _shouldBotProceed($bot_user_id, $match){

      if(empty($bot_user_id)) return false;

      if(empty($match)) return false;

      if(empty( (int)$match->is_initial_odds_enabled)) return false;

      if(empty( (int)$match->team_a_initial_odd)) return false;

      if(empty($match->team_b_initial_odd)) return false;

      return true;
  }

}