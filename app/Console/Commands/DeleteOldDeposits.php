<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Transaction;
use App\TransactionNote;

class DeleteOldDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete 48hrs old depsosits';

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
        $this->info('[' . \Carbon\Carbon::now() . ']');
        $this->info("Scanning for 48 hrs old deposits ....");
        $this->info("");
        $ctr = 0;
        $daysAgo =  \Carbon\Carbon::now()->subDays(2);
        $where = [
            ['status', '=', 'processing'],
            ['created_at', '<=', $daysAgo]
        ];
        $depsosits = \App\Transaction::whereRaw('type = "deposit" and picture is null')
                                ->where($where)
                                ->with('user')
                                ->get();

        foreach ($depsosits as $deposit) {
                // $deposit->delete();
            $deposit->status = 'rejected';
            $deposit->save();
            $note = new TransactionNote;
            $note->transaction_id = $deposit->id;
            $note->user_id = 0;
            $note->message = 'Request expires because it has passed 48 hrs without any update';
            $note->save();
            $this->info("user name: ".(!is_null($deposit->user) ? $deposit->user->name." | deposit bc code: " : "no user | deposit bc code: " ).$deposit->code);
            $ctr++;
            
        }
        $this->info("");
        $this->info("Total deposits rejected: " . $ctr);
        $this->info("");

    }
}
