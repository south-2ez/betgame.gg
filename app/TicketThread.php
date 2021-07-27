<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketThread extends Model
{
    public function commentedBy()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
