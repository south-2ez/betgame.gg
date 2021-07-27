<?php

namespace App\Repositories;

use App\UpdatedMatchOddsLogs;
use Carbon;

class UpdatedMatchOddsLogsRepository {

  public function create($data){
    if(!empty($data)){

      $logs = new UpdatedMatchOddsLogs;
      $logs->match_id = $data['match_id'];
      $logs->message = $data['message'];      
      $logs->save();
      
      return $logs;
    }

    return false;
  }

  public function getUnprocessedUpdates(){
    $updates = UpdatedMatchOddsLogs::where('processed',0)->get();
    return $updates;
  }


}