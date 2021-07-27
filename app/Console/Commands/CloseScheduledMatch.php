<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CloseScheduledMatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hermes:close-matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for closing current scheduled matches...';

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
        $matches = \App\Match::where('status', 'open')->get();
        foreach($matches as $match) {
            if($match->teamA->name == 'TBD' || $match->teamB->name == 'TBD') {
                continue;
            }
            //added 1 minute interval before game start
            if(\Carbon\Carbon::now()->diffInSeconds($match->schedule->copy()->addSeconds(60), false) <= 0 && !$match->re_opened) {
                $this->info('[' . \Carbon\Carbon::now() . '] Found match [Match ID: ' . $match->id . '] ' . $match->name . ' - closing...');
                setupOngoingMatch($match);
            }
        }
    }
}
