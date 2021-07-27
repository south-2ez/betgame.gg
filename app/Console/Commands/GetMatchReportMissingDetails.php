<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MatchReport;
use App\Match;
use App\Team;

class GetMatchReportMissingDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2ez:get-missing-details-of-match-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get missing detail of match reports';

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
        $this->info('Updating match reports - '.date('Y-m-d H:i:s'));
        $matches = MatchReport::all();
        $this->output->progressStart($matches->count());
        foreach ($matches as $match) {
            $team_a_data = $match->data->team_a;
            $team_b_data = $match->data->team_b;
            $_match = Match::find($match->id);
            $team_a = Team::find($_match->team_a);
            $team_b = Team::find($_match->team_b);
            $team_a_data->id = $team_a->id;
            $team_a_data->name = $team_a->name;
            $team_b_data->id = $team_b->id;
            $team_b_data->name = $team_b->name;
            $data = collect([
                'team_a' => $team_a_data,
                'team_b' => $team_b_data,
            ]);
            $match->data = $data->toJson();
            $match->save();
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->info('End');
    }
}
