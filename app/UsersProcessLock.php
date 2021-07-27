<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersProcessLock extends Model
{
    use SoftDeletes;

    protected $table = 'users_process_lock';
    protected $fillable = ['user_id', 'counter'];

    public function user()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }
}
