<?php

namespace App\Repositories;
use App\BetHistory;

class BetHistoryRepository{

  public function create($data){

    $history = BetHistory::create([
      'type' => 'update',
      'match_id' => $data['match_id'],
      'bet_id' => $data['bet_id'],
      'amount' => $data['amount'],
      'user_id' => $data['amount'],
      'curr_credits' => $data['user_credits']
    ]);

    return $history;

  }
}