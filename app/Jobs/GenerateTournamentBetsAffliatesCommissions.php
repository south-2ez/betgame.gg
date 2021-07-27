<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GenerateTournamentBetsAffliatesCommissions implements ShouldQueue
{
    public $tries = 3;
    protected $data;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $league = $this->data; //league details
  
        $league_id = $league->id; //league id
        $league_settled = $league->updated_at; //settled date
        $league_winner = $league->league_winner; //team winner
        $league_status = $league->status;
        $commission_percentage = env('AFFILIATE_COMMISSIONS_VIA_BETS',0.004) * 100;
        $commission_percentage_multiplier =  env('AFFILIATE_COMMISSIONS_VIA_BETS',0.004);

        $isSubStreamer = false;
        

        if(!empty($league_winner)){ //process only job if league is settled - meaning league needs to have a winner
            
            $league->load('tournamentBets.user'); 
            $bettors = $league->tournamentBets->pluck('user');
            $bets = $league->tournamentBets; //bets on this leauge

            //getting list of affliates that we need to loop thru
            //$affliateBadgesIds = \App\Badge::where('name','AFFILIATE')->pluck('id');
            //$affliateBadgesIds = \App\Badge::whereIn('name',['AFFILIATE','SUB-AFFILIATE'])->pluck('id'); //UPDATE use whereIn to get both main & sub streamer

            $affiliateBadge =  \App\Badge::where('name','AFFILIATE')->first();
            $subAffiliateBadge =\App\Badge::where('name','SUB-AFFILIATE')->first();
            $affliateBadgesIds = [99999];

            if(!empty($affiliateBadge)){
                $affliateBadgesIds[] = $affiliateBadge->id;
            }

            if(!empty($subAffiliateBadge)){
                $affliateBadgesIds[] = $subAffiliateBadge->id;
            }

            $affliates = \DB::table('users')
                        ->join('badge_user','users.id', '=','badge_user.user_id')
                        ->whereIn('badge_user.badge_id',$affliateBadgesIds)
                        ->where('users.voucher_code', '!=', NULL)
                        ->where('users.voucher_percent', '>',0)
                        ->get(); 

            //end getting list of affliates that we need to loop thru
            if(!empty($affliates)){
                
                foreach($affliates as $key => $streamer){

                    if(!empty( $subAffiliateBadge ) && $streamer->badge_id ==  $subAffiliateBadge->id ){
                        $isSubStreamer = true;
                    }

                    // if($isSubStreamer){
                    //     $commission_percentage = env('SUBAFFILIATE_COMMISSIONS_VIA_BETS',0.0032) * 100;
                    //     $commission_percentage_multiplier =  env('SUBAFFILIATE_COMMISSIONS_VIA_BETS',0.0032);
                    // }

                    $commission_percentage = $streamer->voucher_percent;
                    $commission_percentage_multiplier =  $commission_percentage / 100;

                    

                    $voucherCode = strtolower($streamer->voucher_code);
                    $streamersBettors = $bettors->filter(function ($bettor) use ($voucherCode) {
                            // replace stristr with your choice of matching function
                            return strtolower($bettor->redeem_voucher_code) == $voucherCode;
                        });

                    if($streamersBettors->isNotEmpty()){

                        //first we need to make sure that we delete the previous data before 
                        //we create a new one for this league
                        \App\CommissionsBets::where([
                            'league_id' => $league_id,
                            'bet_type' => 'tournament',
                            'belongs_to' => $streamer->id,
                            'type' => 'own'
                        ])->delete();
                        

                        $streamerBettorBets = $bets->whereIn('user_id', $streamersBettors->pluck('id'))->map(function($bet) use ($commission_percentage_multiplier){
                            $temp =  collect($bet)->only([
                                        'id',
                                        'user_id',
                                        'amount'
                                    ]);


                            $temp['name'] = $bet->user->name;
                            $temp['percentage'] = $commission_percentage_multiplier * 100;
                            $temp['earnings'] = $bet->amount * $commission_percentage_multiplier;

                            return $temp;
                        });
                        
                        $totalAmountBetted = $streamerBettorBets->sum('amount');
                        $earnings = $streamerBettorBets->sum('earnings');

                        $commission = \App\CommissionsBets::create([
                            'match_id' => NULL,
                            'league_id' => $league_id,
                            'bet_type' => 'tournament',                            
                            'date_settled' => $league_settled,
                            'amount' => $earnings,
                            'percentage' => $commission_percentage,
                            'belongs_to' => $streamer->id,
                            'user_bets' => $streamerBettorBets->toJson(),
                            'status' => 0,
                        ]);

                        //if affliate/streamer is only sub -> then we need to give the cut to their main affiliate/streamer too
                        //AFFILIATE_COMMISSIONS_VIA_BETS_FROM_SUB
                        if($isSubStreamer){

                            if($voucherCode == 'tims'){
                                $main_streamer_commission_cut_percentage = env('TIMS_AFFILIATE_COMMISSIONS_VIA_BETS_FROM_SUB',0.001) * 100;
                                $main_streamer_commission_cut_percentage_multiplier =  env('TIMS_AFFILIATE_COMMISSIONS_VIA_BETS_FROM_SUB',0.001);
                            }else{
                                $main_streamer_commission_cut_percentage = env('AFFILIATE_COMMISSIONS_VIA_BETS_FROM_SUB',0.0008) * 100;
                                $main_streamer_commission_cut_percentage_multiplier =  env('AFFILIATE_COMMISSIONS_VIA_BETS_FROM_SUB',0.0008);
                            }

                            $main_streamer_id = 585;
                            
                            //first we need to make sure that we delete the previous data before 
                            //we create a new one for this league
                            \App\CommissionsBets::where([
                                'league_id' => $league_id,
                                'match_id' => NULL,
                                'bet_type' => 'tournament',
                                'belongs_to' => $main_streamer_id,
                                'sub_id' => $streamer->id,
                                'type' => 'from-sub'
                            ])->delete();
                            

                            $streamerBettorBets = $bets->whereIn('user_id', $streamersBettors->pluck('id'))->map(function($bet) use ($main_streamer_commission_cut_percentage_multiplier){
                                $temp =  collect($bet)->only([
                                            'id',
                                            'user_id',
                                            'amount'
                                        ]);


                                $temp['name'] = $bet->user->name;
                                $temp['percentage'] = $main_streamer_commission_cut_percentage_multiplier * 100;
                                $temp['earnings'] = $bet->amount * $main_streamer_commission_cut_percentage_multiplier;

                                return $temp;
                            });
                            
                            $totalAmountBetted = $streamerBettorBets->sum('amount');
                            $earnings = $streamerBettorBets->sum('earnings');

                            $commission = \App\CommissionsBets::create([
                                'match_id' => NULL,
                                'league_id' => $league_id,
                                'bet_type' => 'tournament', 
                                'amount' => $earnings,
                                'percentage' => $main_streamer_commission_cut_percentage,
                                'belongs_to' => $main_streamer_id,
                                'user_bets' => $streamerBettorBets->toJson(),
                                'status' => 0,
                                'sub_id' => $streamer->id,
                                'type' => 'from-sub'
                            ]);

                        }

                    }
                }
            }

            Log::info('Done generating commissions data for League ID: ' . $league_id . ' Name : ' . $league->name);
        } 
        
    }
}
