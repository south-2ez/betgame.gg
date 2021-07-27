<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopBettor extends Model
{

    protected $fillables = ['league_id','data'];
    /**
     *  Get the league that associated with the top bettors
     */
    public function league()
    {
        return $this->belongsTo('App\League');
    }

    /**
     * Return data to array
     */
    public function getDataAttribute()
    {
        return  json_decode($this->attributes['data']);
    }



}
