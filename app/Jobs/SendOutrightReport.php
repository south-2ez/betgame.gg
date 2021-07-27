<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\League;

class SendOutrightReport implements ShouldQueue
{   
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
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

        $league = $this->data['league'];
        $teams = $league->teams;
        
        $bets = $league->tournamentBets->load('user');

        $data['total_bettors'] = $bets->count();
        $data['total_bets'] = $bets->sum('amount');
        $data['average_bets'] = $data['total_bettors'] != 0 ? $data['total_bets']/$data['total_bettors'] : 0.00;
        $data['betting_fee'] = $league->betting_fee;
        $data['total_fees_collected'] = $league->league_winner ? $data['total_bets'] * $league->betting_fee : 0.00;
        $data['total_payout'] = $league->league_winner ? $data['total_bets'] - $data['total_fees_collected'] : 0.00;
        $data['circulating_credits_before_settled'] = $league->circulating_credits_before_settled ?? 0.00 ;
        $data['circulating_credits_after_settled'] = $league->circulating_credits_after_settled ?? 0.00;
        $data['teams'] = $teams;
        $data['bets'] = $bets;
        $data['league'] = $league;

        $excelFileName = "{$league->name} - ID ({$league->id})}";
        $excelFile = \Excel::create('Outright Report of '.$excelFileName, function($excel) use($data) {
            $excel->sheet('Overview', function($sheet) use($data) {
                $sheet->loadView('emails.outright-report', array('data' => $data));
            });
        });
                
        \Mail::raw('See attached excel file for the Outright report. | League Name: '.$league->name.' | League ID : '.$league->id, function($message) use ($excelFile,$league) {
            $message->from('admin@2ez.bet', '2ez.bet Admin');
            $message->to('reports@2ez.bet')
            ->cc('brandnewbien@gmail.com')
            ->cc('south.2ez@gmail.com')
            ->subject('Outright Report of '.$league->name);
            $message->attach($excelFile->store("xlsx", false, true)['full']);
        });

        Log::info('Sent Outright Report of '.$league->name);

    }
}
