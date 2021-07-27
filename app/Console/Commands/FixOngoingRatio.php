<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixOngoingRatio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hermes:fixongoingratio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to fix null ratio on matches and bets';

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
        $matches = \App\Match::where('status', 'ongoing')->get()->load('bets');
        $query = \DB::table('bets')->whereIn('match_id', $matches->pluck('id'))
                    ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                    ->groupBy('match_id', 'team_id')->get();
        foreach ($matches as $match) {
            $match_details = $query->where('match_id', $match->id);
            $team_a_ratio = 2;
            $team_b_ratio = 2;
            if($match_details->count()) {
                $total_bets = $query->where('match_id', $match->id)->sum('total_bets');
                $team_a_bets = $query->where('match_id', $match->id)->where('team_id', $match->team_a)->sum('total_bets');
                $team_a_ratio = $team_a_bets ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;

                $team_b_bets = $query->where('match_id', $match->id)->where('team_id', $match->team_b)->sum('total_bets');
                $team_b_ratio = $team_b_bets ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;
            }

            // $team_a_ratio = number_format($team_a_ratio, 2, '.', ',');
            // $team_b_ratio = number_format($team_b_ratio, 2, '.', ',');
            $team_a_ratio  = bcdiv($team_a_ratio, 1 ,2);
            $team_b_ratio  = bcdiv($team_b_ratio, 1 ,2);


            // $match->team_a_ratio = number_format($match->team_a_ratio, 2, '.', ',');
            // $match->team_b_ratio = number_format($match->team_b_ratio, 2, '.', ',');
            $match->team_a_ratio = bcdiv($match->team_a_ratio, 1 ,2);
            $match->team_b_ratio = bcdiv($match->team_b_ratio, 1 ,2);

            if ($team_a_ratio != $match->team_a_ratio || $team_b_ratio != $match->team_b_ratio) {
                $this->info('[' . \Carbon\Carbon::now() . '] Found match [Match ID: ' . $match->id . '] ' . $match->name . 'A: ' .$team_a_ratio. ' vs B: ' .$team_b_ratio.' - fixing...');
                $match->team_a_ratio = $team_a_ratio;
                $match->team_b_ratio = $team_b_ratio;
                $match->save();
            }

            foreach ($match->bets as $bet) {
                $_ratio = $bet->team_id == $match->team_a ?
                        $match->team_a_ratio : $match->team_b_ratio;

                // $_ratio = number_format($_ratio, 2, '.', ',');
                $_ratio = bcdiv($_ratio, 1 ,2);
                // $bet->ratio = number_format($bet->ratio, 2, '.', ',');
                $bet->ratio = bcdiv($bet->ratio,1, 2);
                
                $currentRatio = !empty($bet->ratio) ? $bet->ratio : 'NOT SET';

                if ($bet->ratio != $_ratio) {
                    $this->info('[' . \Carbon\Carbon::now() . '] Found match [Match ID: ' . $match->id . ', Bet ID: ' . $bet->id . ', Current Ratio: '.$currentRatio.', New Ratio: '.$_ratio.'] ' . $match->name . ' - fixing...');
                    $bet->ratio = $_ratio;
                    $bet->save();
                }
            }
        }
    }
}
