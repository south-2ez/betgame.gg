<?php

namespace App\Repositories;

use App\Match;

class MatchRepository {

  public function findMatchById($id = 0){
    return Match::find($id);
  }

  public function getOpenMatches(){
    return Match::where('matches.status', 'open')->get();
  }

  public function getEnabledBotOpenMatches(){
    $where = [
      'status' => 'open',
      'is_initial_odds_enabled' => 1
    ];
    return Match::where($where)->get();
  }

  public function getMatchesWhere($where = null){
    if(!empty($where)){
        return Match::where($where)->get();
    }

    return null;
  }

  //get amount to less of betbot based on how much remaining time
  //of scheduled time
  public function getAmountToLess($atMinMark){
    if($atMinMark >= 10){
      return 0.05;
    }else{
      return 0.02;
    }
  }
}