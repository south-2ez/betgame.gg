<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Referal;
use Cache;

class User extends Authenticatable
{
    use Notifiable;
    use HasPushSubscriptions;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id', 'avatar','play_money', 'verified','real_money', 'banned_until', 'redeem_voucher_code'
    ];

    protected $dates = [
        'banned_until'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function isAdmin()
    {
        return $this->attributes['type'] == 'admin';
    }
    
    public function isMatchManager()
    {
        return $this->attributes['type'] == 'matchmanager';
    }
    
    public function isAgent()
    {

        $isAgentCacheKey = 'isAgent_' . getUserCacheKey();
        return Cache::remember( $isAgentCacheKey, 60 , function (){
    
            return !$this->roles->where('pivot.role_id',2)->isEmpty() ?  true : false;
        
        });

        
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function commissions()
    {
        return $this->hasMany('App\Commission','belongs_to');
    }

    public function partnerCommissions()
    {
        return $this->hasMany('App\CommissionsPartner','belongs_to');
    }

    public function commissionBets()
    {
        return $this->hasMany('App\CommissionsBets','belongs_to');
    }

    public function deposit_commissions()
    {
        return $this->hasMany('App\DepositCommission','belongs_to');
    }

    public function unpaid_commissions()
    {
        return $this->commissions()->unpaid();
    }

    public function unpaid_partner_commissions()
    {
        return $this->partnerCommissions()->unpaid();
    }

    public function unpaid_bets_commissions()
    {
        return $this->commissionBets()->unpaid();
    }   
    
    public function bets()
    {
        return $this->hasMany('App\Bet');
    }

    public function spins()
    {
        return $this->hasMany('App\RouletteSpins');
    }
    
    public function getTournamentBetPerTeam($league_id, $team_id)
    {
        return $this->bets()->tournament($league_id)->where('team_id', $team_id)->sum('amount');
    }
    
    public function getMatchBetAmount($match_id, $opt_amount = 0, $team_id = 0)
    {
        if($this->bets->where('match_id', $match_id)->count())
            return $team_id ? $this->bets->where('match_id', $match_id)
                ->where('team_id', $team_id)->sum('amount') + $opt_amount :
                $this->bets->where('match_id', $match_id)->sum('amount') + $opt_amount;
        else
            return $opt_amount;
    }

    public function donations()
    {
        return $this->hasMany('App\Donation');
    }
    
    public function badges()
    {
        return $this->belongsToMany('App\Badge');
    }

    public function transactedDiscrepancy()
    {
        return $this->hasMany('App\TransactionDiscrepancy');
    }

    public function totalPayableCommission()
    {
        // $total = 0;
        // $commissions = $this->commissions->where('status',0)->where('transaction.status','completed');
        // return $commissions->sum('amount');


        $commissions = DB::table('commissions')
                                ->selectRaw(' sum(commissions.amount) as amount ')
                                ->join('transactions','transactions.id','=','commissions.transaction_id')
                                ->where(
                                    [
                                        'commissions.belongs_to' => $this->id,
                                        'commissions.status' => 0,
                                        'transactions.status' => 'completed'
                                    ])
                                ->first();


        return $commissions->amount;

    }    

    public function totalCommission()
    {
        // $total = 0;
        // $commissions = $this->commissions->where('transaction.status','completed');
        // $total = $commissions->sum('amount');

        // return $total;

        $commissions = DB::table('commissions')
                                ->selectRaw(' sum(commissions.amount) as amount ')
                                ->join('transactions','transactions.id','=','commissions.transaction_id')
                                ->where(
                                    [
                                        'commissions.belongs_to' => $this->id,
                                        'transactions.status' => 'completed'
                                    ])
                                ->first();


        return $commissions->amount;
        

    }

    public function totalPayablePartnerCommission()
    {
        // $total = 0;
        // $commissions = $this->partnerCommissions->where('status',0)->where('transaction.status',1);
        // return $commissions->sum('amount');
        // foreach ($commissions as $key => $commission) {
        //     $total+=$commission->amount;
        // }
        // return $total;

        $commissions = DB::table('commissions_partners')
                                ->selectRaw(' sum(commissions_partners.amount) as amount ')
                                ->join('partner_transactions','partner_transactions.id','=','commissions_partners.partner_transaction_id')
                                ->where(
                                    [
                                        'commissions_partners.belongs_to' => $this->id,
                                        'commissions_partners.status' => 0,
                                        'partner_transactions.status' => 1
                                    ])
                                ->first();


        return $commissions->amount;        


    }    

    public function totalPartnerCommission()
    {
        // $total = 0;
        // $commissions = $this->partnerCommissions->where('transaction.status',1);
        // $total = $commissions->sum('amount');

        // return $total;

        $commissions = DB::table('commissions_partners')
                                ->selectRaw(' sum(commissions_partners.amount) as amount ')
                                ->join('partner_transactions','partner_transactions.id','=','commissions_partners.partner_transaction_id')
                                ->where(
                                    [
                                        'commissions_partners.belongs_to' => $this->id,
                                        'partner_transactions.status' => 1
                                    ])
                                ->first();


        return $commissions->amount;       


    }

    public function totalPayableCommissionBets()
    {
        return $this->commissionBets->where('status',0)->sum('amount');
    }    

    public function totalCommissionBets()
    {
        return $this->commissionBets->sum('amount');
    }

    
    public function scopeAdmins($query)
    {
        return $query->where('type','admin');
    }

    /**
     * Query only agent type user
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAgents($query)
    {
        return $query->where('type','agent');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    /**
     * The roles that belong to the user.
     */
    public function referals()
    {
        return $this->hasMany('App\Referal','belongs_to');
    }

    /**
     * The reported bugs that belong to the user.
     */
    public function reportedBugs()
    {
        return $this->hasMany('App\ReportedBug');
    }

    /**
     * The reported bugs that belong to the user.
     */
    public function proccessedBugs()
    {
        return $this->hasMany('App\ReportedBug','proccessed_by');
    }

    /**
     * The reported promotion that belong to the user.
     */
    public function promotions()
    {
        return $this->hasMany('App\Promotion');
    }

    /**
     * The reported promotion that belong to the user.
     */
    public function proccessedPromotions()
    {
        return $this->hasMany('App\Promotion','proccessed_by');
    }
    
    public function rewards()
    {
        return $this->hasMany('App\Reward');
    }
    
    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
    
    /* Partner Connections */
    public function userPartner()
    {
        return $this->hasOne('App\Partner', 'user_id');
    }

    public function subUserPartner()
    {
        return $this->hasOne('App\PartnerSubUsers', 'user_id');
    }

    public function userTransactions()
    {
        return $this->hasMany('App\PartnerTransaction', 'user_id');
    }

    /**
     * The item purchase by users.
     */
    public function purchase()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * The wallet of users.
     */
    public function wallet()
    {
        return $this->hasMany('App\Wallet');
    }

    /**
     * The rebates of users.
     */
    public function rebates()
    {
        return $this->wallet()->where('type','rebate');
    }
 
    /**
     * not transafered wallet of users.
     */
    public function activeWallet()
    {
        return $this->wallet()->notTransfered();
    }
 
    /**
     * The total not transafered wallet of users.
     */
    public function totalActiveWallet()
    {
        return $this->activeWallet()->get()->sum('collected');
    }

    /**
     * Event for user
     */
    public static function boot() {
        parent::boot();
        
        static::created(function($user) {

            $user->referal_code = $user->generateCode($user->id);
            $user->save();

            /**
             * For those who sign up with referals
             */

            $ref_code = !empty(request()->input('referral_code')) ? request()->input('referral_code') : (request()->session()->get('referral_code') ?? '');

            if(!empty($ref_code)){
                // if (request()->hasSession() && request()->session()->has('referral_code')) {
                    // $ref_code = request()->session()->get('referral_code');
                    $_user = User::where('referal_code', $ref_code)->first();
                    if ($_user) {
                        $ref_count = Referal::where([
                                    ['code', $ref_code],
                                    ['belongs_to', $_user->id],
                                    ['refered_user', $user->id]
                                ])->count();
                        if ($ref_count == 0) {
                            $referal = new Referal;
                            $referal->code = $ref_code;
                            $referal->belongs_to = $_user->id;
                            $referal->refered_user = $user->id;
                            $referal->fee = 0.00;
                            if($user->provider == 'facebook'){
                                $referal->verified = 1;
                                $referal->save();
                                // $_user->credits += 100.00; # Remove for real money betting
                                // $_user->save();
                            
                                // \App\Reward::create([
                                //     'user_id' => $_user->id,
                                //     'type' => 'referral',
                                //     'class_id' => $referal->id,
                                //     'description' => 'Awarded Referral: ' . $referal->code . ' with ' . $referal->fee . ' credits',
                                //     'credits' => $referal->fee
                                // ]); # Remove for real money betting

                            }else{
                                $referal->verified = 0;
                                $referal->save();
                            }
                        }
                    }
                // }
            }

        });

        static::updated(function($user) {
            if($user->credits < 0){
                \Log::info("User {$user->id} credits negative, current credits: {$user->credits} ... alerting admin.");
                //notify admin
                app('App\Http\Controllers\PushController')->pushNegativeCreditsUserAlert($user);
            }

            if($user->isDirty('type') && $user->type == 'admin'){

                $currentIp = request()->ip();
                \Mail::raw('ADMIN changed user to ADMIN access | ' . $currentIp, function($message) use($currentIp, $user)  {
                    $message->from('admin@2ez.bet', '2ez.bet Admin');
                    $message->to('aljun.demetria@gmail.com')
                    ->subject( \Auth::user()->name .  ' changed user ' . $user->name. ' to ADMIN | ' . $currentIp);
                });                
            }
           
        });
    }

    /**
     * Generate code for referals
     * @param int $user_id
     * @return string code
     */
    public function generateCode($user_id) 
    { 
    	do{
		    $chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz"; 
		    srand((double)microtime()*1000000); 
		    $i = 0; 
		    $pass = '' ; 

		    while ($i <= 7) { 
		        $num = rand() % 33; 
		        $tmp = substr($chars, $num, 1); 
		        $pass = $pass . $tmp; 
		        $i++; 
		    }
		    $code = $pass;
    	}while (!User::find($user_id)->where('referal_code',$code)->get()->isEmpty());

	    return $code; 

    } 
    
    public function giftCodes()
    {
        return $this->hasMany('App\GiftCode');
    }

    public function giftCodesAttempts()
    {
        return $this->hasMany('App\GiftCodesAttempt');
    }   
    
    public function messages()
    {
        return $this->hasMany('App\UserMessages');
    }

    public function verified_by()
    {
        return $this->hasMany('App\VerifiedBy','user_id');
    }
}
