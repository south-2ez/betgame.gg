<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $appends = ['transaction'];
    
    public function getTransactionAttribute()
    {
        switch ($this->attributes['meta_key']) {
            case 'cashout':
                return \App\Transaction::find($this->attributes['meta_value']);
            case 'cashout-partner':
                return \App\PartnerTransaction::find($this->attributes['meta_value']) ;
            case 'match':
                return \App\MatchReport::find($this->attributes['meta_value']);
            case 'tournament':
                return \App\League::find($this->attributes['meta_value']);
            default:
                # code...
                break;
        }
    }
}
