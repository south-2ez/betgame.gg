<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerPayout extends Model
{
    public function partner(){
        return $this->belongsTo('\App\Partner', 'partner_id');
    }
    public function processBy()
    {
        return $this->belongsTo('App\User', 'process_by');
    }
}
