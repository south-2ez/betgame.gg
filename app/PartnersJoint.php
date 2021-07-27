<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnersJoint extends Model
{
    use SoftDeletes;

    protected $table = 'partners_joint';
    protected $fillable = ['partner_id', 'streamer_user_id'];
    
    public function user()
    {
        return $this->belongsTo('\App\User', 'streamer_user_id');
    }
}
