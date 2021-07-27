<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MissingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hermes:missing-report {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get missing report';

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
        switch ($this->argument('type')) {
            case 'match':
                $_match = \App\Match::whereDate('matches.updated_at','>=','2018-06-27')->whereRaw('matches.league_id = 26 and matches.status = "settled"')
                         ->join('match_reports','match_reports.id','=','matches.id');
                break;
            
            default:
                # code...
                break;
        }
    }
}
