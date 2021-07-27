<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftCode extends Model
{
    protected $table = 'gift_codes';
    protected $fillable = ['code', 'amount', 'description', 'generated_by', 'user_id','status', 'date_redeemed', 'purpose', 'give_to'];
    
    public function generatedBy()
    {
        return $this->belongsTo('App\User', 'generated_by');
    }

    public function usedBy()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function scopeSearch($query, $text)
    {
        return $query->orWhere('code','like','%' . $text . '%');
    }

    public function scopeUsedToday($query)
    {
        $today = date('Y-m-d');
        return $query->whereDate('date_redeemed',$today)->where('status',1);
    }

    public function scopeClaimables($query)
    {
        return $query->whereIn('purpose', [1,2]);
    }
}
