<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionsPartner extends Model
{
    use SoftDeletes;

    protected $casts = ['status' => "boolean"];
    
    public function transaction()
    {
    	return $this->belongsTo('App\PartnerTransaction','partner_transaction_id');
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
