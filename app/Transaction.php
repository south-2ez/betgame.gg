<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\LogUserCredits;
use Carbon\Carbon;

class Transaction extends Model
{

    protected $fillable = ['amount','type','code','data','process_by','remaining_balance'];
    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }
    
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function userOwner()
    {
    	return $this->belongsTo('App\User','user_id');
    }   
    
    public function processBy()
    {
        return $this->belongsTo('App\User', 'process_by');
    }
    
    public function donation()
    {
        return $this->hasOne('App\Donation');
    }
    
    public function commission()
    {
        return $this->hasOne('App\Commission');
    }
    
    public function deposit_commission()
    {
        return $this->hasOne('App\DepositCommission');
    }
    
    public function notes()
    {
        return $this->hasMany('App\TransactionNote');
    }
    
    public function discrepancy()
    {
        return $this->hasMany('App\TransactionDiscrepancy');
    }
    
    public function scopeCurrentYearUp($query)
    {
        return $this->whereRaw('year(created_at) >= 2018');
    }
    
    public function scopeCompleted($query)
    {
        return $query->whereRaw('status = "completed"');
    }
    
    public function scopeCompletedByDate($query,$date)
    {
        return $query->whereRaw('status = "completed" and date(updated_at) = "'.$date.'"');
    }

    public static function boot()
    {
        parent::boot();
        
        // cause a cascade delete to all children references for this user
        static::deleting(function($user) {
            $user->notes()->delete();
            $user->discrepancy()->delete();
        });

        static::created(function($transaction) {
            //notify admin
            app('App\Http\Controllers\PushController')->pushNewTransactionViaDirect($transaction);
        });

        static::updated(function($transaction) {
            //triggers when deposit is accepted
            if($transaction->getOriginal('status') == 'processing' && $transaction->status == 'completed') {
                $log = [
                    'user_id'        => $transaction->user_id,
                    'reference_id'   => $transaction->id,
                    'model'          => 'Transaction',
                    'action'         => strtoupper($transaction->type),
                    'amount'         => $transaction->amount,
                    'current_credit' => $transaction->remaining_credits,
                    'new_credit'     => $transaction->new_credits,
                    'created_at'     => \DB::raw("now()"),
                    'updated_at'     => \DB::raw("now()")
                ];

                // $job = (new LogUserCredits($log))
                //     ->onConnection('redis')
                //     ->onQueue('low')
                //     ->delay(Carbon::now()->addMinutes(2));

                // dispatch($job);
                dispatch(new LogUserCredits($log));
            }
        });
    }
}
