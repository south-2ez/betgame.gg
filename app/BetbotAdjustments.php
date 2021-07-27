<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetbotAdjustments extends Model
{
     protected $fillable = ['match_id', 'match_schedule', 'adjusted_bet_ids', 'adjusted_amount', 'adjusted_at'];
}
