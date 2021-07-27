<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
    public function owner()
    {
    	return $this->belongsTo('App\User','belongs_to');
    }

    public function referedUser()
    {
    	return $this->belongsTo('App\User','refered_user');
    }

    public function scopeVerified($query)
    {
        $query->where('verified',1);
    }
}
