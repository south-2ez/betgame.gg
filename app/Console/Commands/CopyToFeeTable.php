<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MatchReport;
use App\Fee;
use App\Donation;

class CopyToFeeTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hermes:copy-to-fee-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy fees collected to fee table';

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
        $this->info('Transfering - '.date('Y-m-d H:i:s'));
        $this->info('Match Report...');
        $match_report = MatchReport::whereDate('created_at','>=','2018-05-29')->where('match_fee','!=','0.000');
        $this->output->progressStart($match_report->count());
        foreach ($match_report->get() as $match) {
            if(!Fee::where(['meta_key'=>'match','meta_value'=>$match->id])->first()){
                $fee = new Fee;
                $fee->meta_key = 'match';
                $fee->meta_value = $match->id;
                $fee->percent = $match->match_fee;
                $fee->collected = $match->total_fees_collected;
                $fee->created_at = $match->created_at;
                $fee->updated_at = $match->created_at;
                $fee->save();
                $this->output->progressAdvance();
                
            }
        }
        $this->output->progressFinish();
        $this->info('End Match Report...');
        $this->info('Donation...');
        $donations = Donation::whereDate('created_at','>=','2018-05-23');
        $this->output->progressStart($donations->count());
        foreach ($donations->get() as $donation) {
            if(!Fee::where(['meta_key'=>'match','meta_value'=>$match->id])->first()){
                $fee = new Fee;
                $fee->meta_key = 'cashout';
                $fee->meta_value = $donation->transaction_id;
                $fee->percent = '0.05';
                $fee->collected = $donation->amount;
                $fee->created_at = $donation->created_at;
                $fee->updated_at = $donation->created_at;
                $fee->save();
                $this->output->progressAdvance();
            }
        }
        $this->output->progressFinish();
        $this->info('End Donation...');

    }
}
