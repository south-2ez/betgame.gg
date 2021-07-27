<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public function partner(){
        return $this->belongsTo('App\Partner', 'id', 'province_id');
    }
}
