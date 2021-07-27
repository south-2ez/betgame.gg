<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillables = ['user_id','link','picture','comment','admin_comment'];

    /**
     *  Get the user that associated with promotion
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the user who proccessed the promotion
     */
    public function processBy()
    {
        return $this->belongsTo('App\User','proccessed_by');
    }
}
