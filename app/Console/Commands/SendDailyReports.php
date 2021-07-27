<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MatchReport;
use App\User;
use App\Transaction;
use \DateTime;
use App\Report;
use App\PartnerTransaction;

class SendDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hermes:daily-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily reports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = new DateTime;
        $yesterday = $now->modify('-1 day');
        $this->info('=== Daily reports  | '.$yesterday->format('Y-m-d').' ===');
        $this->info('Processing...');
        
        $total_available_credits = number_format(str_replace(",","",calculateCredits('total_available_credits')),2,'.','');
        $total_circulating_credits = number_format(str_replace(",","",calculateCredits('total_circulating_credits')),2,'.','');
        $total_approved_deposits = 0;
        $total_approved_cashouts = Transaction::completedByDate($yesterday->format('Y-m-d'))->where('type','cashout')
            ->select(\DB::raw('sum(amount) as total'))
            ->first()->total;
        $total_cashout_fees_collected = Transaction::completedByDate($yesterday->format('Y-m-d'))->get()->load('donation')->sum('donation.amount');
        $total_cashout_fees_collected_via_partner = PartnerTransaction::completedByDate($yesterday->format('Y-m-d'))->get()->load('donation')->sum('donation.amount');
        $total_buy_credits = PartnerTransaction::completedByDate($yesterday->format('Y-m-d'))->where('trade_type','partner-admin')->where('type','deposit')->select(\DB::raw('sum(amount) as total'))->first()->total;
        $total_sell_credits = PartnerTransaction::completedByDate($yesterday->format('Y-m-d'))->where('trade_type','partner-admin')->where('type','cashout')->select(\DB::raw('sum(amount) as total'))->first()->total;
        $deposits = Transaction::completedByDate($yesterday->format('Y-m-d'))->where('type','deposit')->get()->load('discrepancy');
        foreach ($deposits as $deposit) {
            if ($deposit->discrepancy->isEmpty()) {
                $total_approved_deposits += $deposit->amount;
            }else{
                if ($deposit->discrepancy->last()->amount) {
                    $total_approved_deposits += $deposit->discrepancy->last()->amount;
                }else{
                    $total_approved_deposits += $deposit->amount;
                }
            }
        }
        Report::create([
            'meta_key' => 'total_circulating_credits',
            'date' => $yesterday->format('Y-m-d'),
            'data' => json_encode(['total'=>$total_circulating_credits])
        ]);
        Report::create([
            'meta_key' => 'total_available_credits',
            'date' => $yesterday->format('Y-m-d'),
            'data' => json_encode(['total'=>$total_available_credits])
        ]);
        $matches = MatchReport::whereDate('created_at',$yesterday->format('Y-m-d'));
        $total_bet_amount = 0.00; $average_bet_amount = 0.00; $total_bettors = 0;$total_fee = 0.00;
        foreach ($matches->get() as $match) {
            $total_bet_amount += $match->total_match_bet;
            $average_bet_amount += $match->average_match_bet;
            $total_bettors += $match->total_bettors;
            $total_fee += $match->total_fees_collected;
        }
        $data['total_matches'] = $matches->count();
        $data['total_bets'] = $total_bet_amount;
        $data['average_bet_amount'] = $total_bet_amount != 0.00 && $matches->count() != 0 ? $total_bet_amount/$matches->count() : 0.00;
        $data['total_bettors'] = $total_bettors;
        $data['average_bettors'] = $matches->count() != 0 ? $total_bettors/$matches->count() : $total_bettors;
        $data['total_fee'] = $total_fee;
        $data['average_fee'] = $matches->where('match_fee','!=','0.000')->count() != 0 ? $total_fee/$matches->where('match_fee','!=','0.000')->count() : $total_fee;
        $data['total_approved_deposits'] = $total_approved_deposits;
        $data['total_approved_cashouts'] = $total_approved_cashouts;
        $data['diff_deposists_cashouts'] = $total_approved_deposits - $total_approved_cashouts;
        $data['total_buy_credits'] = $total_buy_credits;
        $data['total_sell_credits'] = $total_sell_credits;
        $data['diff_buy_sell'] = $total_buy_credits - $total_sell_credits;
        $data['total_cashout_fees_collected'] = $total_cashout_fees_collected;
        $data['total_cashout_fees_collected_via_partner'] = $total_cashout_fees_collected_via_partner;
        $data['total_earned_today'] = $total_fee + $total_cashout_fees_collected + $total_cashout_fees_collected_via_partner;
        $data['date'] = $yesterday->format('Y-m-d');    
        $excelFile = \Excel::create('Daily Report - '.$yesterday->format('Y-m-d'), function($excel) use($data) {
            $excel->sheet('Overview', function($sheet) use($data) {
                $sheet->loadView('emails.daily-report', array('data' => $data));
            });
        });
                
        if(\App::environment('prod')){
            \Mail::raw('See attached excel file for the daily report. | '.$yesterday->format('Y-m-d'), function($message) use ($excelFile,$yesterday) {
                $message->from('admin@2ez.bet', '2ez.bet Admin');
                $message->to('reports@2ez.bet')
                ->cc('brandnewbien@gmail.com')
                ->cc('south.2ez@gmail.com')
                ->subject('Daily Report of '.$yesterday->format('Y-m-d'));
                $message->attach($excelFile->store("xlsx", false, true)['full']);
            });
        }
        $this->info('=== End daily reports  | '.$now->format('Y-m-d').' ===');
    }
}
