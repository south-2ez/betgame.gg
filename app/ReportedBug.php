<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportedBug extends Model
{
    protected $fillables = ['user_id','proccessed_by','picture','comment','admin_comment'];
    protected $appends = ['hasImage'];
    protected $hidden = ['picture'];

    /**
     *  Get the user that associated with reported bug
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function getHasImageAttribute()
    {
        return $this->attributes['picture'] ? true : false;
    }

    /**
     * Get the user who proccessed the reported bug
     */
    public function processBy()
    {
        return $this->belongsTo('App\User','proccessed_by');
    }
    
    /**
     * Get the threads of the bug case
     */
    public function thread()
    {
        return $this->hasMany('App\TicketThread');
    }

    public static function boot()
    {
        parent::boot();
        
        static::created(function($bug) {
            //notify admin
            app('App\Http\Controllers\PushController')->pushNewReportedBug($bug);
        });
    }
}
