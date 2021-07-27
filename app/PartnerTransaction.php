<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerTransaction extends Model
{
    public function partner()
    {
        return $this->belongsTo('\App\Partner', 'partner_id');
    }

    public function parentPartner()
    {
        return $this->belongsTo('\App\Partner', 'main_partner_id');
    }

    public function partnerTransactions()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function processBy()
    {
        return $this->belongsTo('App\User', 'process_by');
    }
    public function discrepancy()
    {
        return $this->hasMany('App\PartnerDiscrepancy');
    }
    
    public function donation()
    {
        return $this->hasOne('App\PartnerDonation');
    }
    
    public function scopeCompletedByDate($query,$date)
    {
        return $query->whereRaw('status = 1 and date(updated_at) = "'.$date.'"');
    }

    public function scopePartnerUserTransactions($query)
    {
        return $query->where('trade_type', 'partner-user');
    }
}
