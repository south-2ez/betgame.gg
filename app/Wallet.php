<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $appends = ['transaction'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'transfered' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function scopeType($query,$type='rebate')
    {
        return $query->whereType($type);
    }
    
    public function scopeNotTransfered($query)
    {
        return $query->whereTransfered(false);
    }
    
    public function getTransactionAttribute()
    {
        switch ($this->attributes['meta_key']) {
            case 'deposit_commission':
            case 'deposit':
                return \App\Transaction::find($this->attributes['meta_value'])->load('discrepancy');
            default:
                # code...
                break;
        }
    }
}
