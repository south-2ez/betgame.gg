<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Match;

class UpdatedMatchOddsLogs extends Model
{
    protected $fillable = [ 'match_id', 'message', 'type' ];

    public function match(){
        return $this->belongsto(Match::class);
    }
}
