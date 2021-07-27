<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetbotLessAdjustments extends Model
{
    protected $fillable = [ 'bet_id', 'amount' ];
}
