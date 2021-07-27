<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function process_by()
    {
        return $this->belongsTo('App\User','process_by');
    }
}
