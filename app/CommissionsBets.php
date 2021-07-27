<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionsBets extends Model
{
    use SoftDeletes;

    protected $table = 'commissions_bets';

    protected $fillable = [
        'match_id', 
        'date_settled', 
        'amount', 
        'percentage', 
        'belongs_to',
        'user_bets',
        'status',
        'type',
        'sub_id'
    ];
    
    public function match()
    {
        return $this->belongsTo('App\Match','match_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User','belongs_to');
    }

    public function subOwner()
    {
        return $this->belongsTo('App\User','sub_id');
    }

    public function getUserBetsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Scope a query to only include unpaid status
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status',0);
    }
}
