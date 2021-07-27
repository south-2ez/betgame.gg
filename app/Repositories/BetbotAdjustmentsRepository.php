<?php

namespace App\Repositories;

use App\BetbotAdjustments;
use Carbon;

class BetbotAdjustmentsRepository {

  public function create($data){
    if(!empty($data)){

      $adjustment = new BetbotAdjustments;
      $adjustment->match_id = $data['match_id'];
      $adjustment->match_schedule = $data['match_schedule'];
      $adjustment->adjusted_bet_ids = $data['adjusted_bet_ids'];
      $adjustment->adjusted_amount = $data['adjusted_amount'];
      $adjustment->adjusted_at = $data['adjusted_at'];
      $adjustment->save();
      
      return $adjustment;
    }

    return false;
  }

  public function getAdjustmentsWhere($where){
    if(!empty($where)){

      $adjustments = BetbotAdjustments::where($where)->get();
      return $adjustments;
    }

    return false;
  }

  public function hasDoneAdjustment($where){
    if(!empty($where)){

      $adjustment = BetbotAdjustments::where($where)->first();
      return !empty($adjustment);
    }

    return false;    
  }

  public function getAdjustmentMark($schedule){
    $parsedSchedule = Carbon::parse($schedule);
    $now = Carbon::now();
    $currentDiffMins = $now->diffInMinutes($parsedSchedule);
    $inMinMark = false;

    // $minRanges = [
    //   60 => range(46, 60),
    //   45 => range(31, 45),
    //   30 => range(16, 30),
    //   15 => range(6, 15),
    //   5 => [5],
    //   4 => [4],
    //   3 => [3],
    //   2 => [2],
    //   1 => [1]
    // ];


    $minRanges = [
      60 => range(51, 60),
      50 => range(41, 50),
      40 => range(31, 40),
      30 => range(21, 30),
      20 => range(11, 20),
      10 => range(6, 10),
      5 => [5],
      4 => [4],
      3 => [3],
      2 => [2],
      1 => [1]
    ];

    foreach($minRanges as $key => $minMark){
      if(in_array($currentDiffMins, $minMark)){
        $inMinMark = $key;
        break;
      }
    }


    return $inMinMark;

  }
}