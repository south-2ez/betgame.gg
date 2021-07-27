<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name', 'description', 'image', 'credits'];
    
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    
    public static function boot() {
        parent::boot();
        
        static::deleting(function($badge) {
            unlink(public_path() . '/' . $badge->image);
        });
    }
}
