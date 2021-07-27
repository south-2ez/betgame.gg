<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositCommission extends Model
{
    protected $casts = ['status' => "boolean"];
    
    public function transaction()
    {
    	return $this->belongsTo('App\Transaction');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User','belongs_to');
    }

    /**
     * Scope a query to only include unpaid status
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status',false);
    }
}
