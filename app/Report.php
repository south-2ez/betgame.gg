<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['meta_key','meta_value','data','date'];

    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }
}
