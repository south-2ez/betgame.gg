<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerProfile extends Model
{
    public function partner(){
        return $this->belongsTo('\App\Partner', 'partner_id');
    }
}
