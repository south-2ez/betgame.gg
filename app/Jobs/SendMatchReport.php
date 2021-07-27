<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\MatchReport;
use App\Match;
use App\Team;
use App\Transaction;
use App\User;
use App\Fee;
use Illuminate\Support\Facades\Log;
use App\Repositories\BetRepository;

class SendMatchReport implements ShouldQueue
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
    public function handle(Match $match)
    {   
        $betRepo = new BetRepository;
        switch ($this->data['type']) {
            case 'settled':
                //slightly optimized code
                $match_id = $this->data['match_id'];
                Log::info('Generating Match Report ID: ' . $match_id);

                $_match = MatchReport::find($match_id);

                if(empty($_match)){
   
                    $match = $match->find($match_id)->load('teamA','teamB');
                    $total_team_a_bettors=0;
                    $total_team_a_bets=0;
                    $total_team_a_profit=0;
                    $total_team_b_bettors=0;
                    $total_team_b_bets=0;
                    $total_team_b_profit=0;
                    $user_a = collect();
                    $user_b = collect();
            
                    if($match->team_a == $match->team_winner && $match->status =='settled'){
                        $status_a = 'win';
                    }elseif ($match->status =='draw'){
                        $status_a = 'draw';
                    }elseif ($match->team_a != $match->team_winner && $match->status =='settled') {
                        $status_a = 'lost';
                    }elseif ($match->status =='open' || $match->status =='ongoing') {
                        $status_a = 'ongoing';  
                    }elseif ($match->status =='cancelled'){
                        $status_a = 'cancelled';
                    }
            
                    $bets = $betRepo->getBetsByMatchId($match->id, ['id','amount','team_id','user_id','gains']);
                    $bets->load('team');
                    $bets->load('user');   
            
                    $teamAbets = $bets->where('team_id',$match->team_a);
                    $teamBbets = $bets->where('team_id',$match->team_b);
            
                    $total_bets = $bets->sum('amount');
                    $total_team_a_bets = $teamAbets->sum('amount');
                    $total_team_b_bets = $teamBbets->sum('amount');
            
                    $total_team_a_bettors = $teamAbets->count();
                    $total_team_b_bettors = $teamBbets->count();
            
                    foreach ($teamAbets as $bet) {
            
                        $profit = $profit = $bet->gains;
                        if($bet->team_id == $match->team_winner && $match->status =='settled'){
                            $total_team_a_profit+=$profit;
                            $status_a = 'win';
                            $_profit = $profit;
                        }elseif ($match->status =='draw'){
                            $status_a = 'draw';
                            $_profit = 0.00;
                        }elseif ($bet->team_id != $match->team_winner && $match->status =='settled') {
                            $total_team_a_profit+=$bet->amount;
                            $status_a = 'lost';
                            $_profit = $bet->amount;
                        }elseif ($match->status =='open' || $match->status =='ongoing') {
                            $status_a = 'ongoing';
                            $_profit = 0.00;
                        }elseif ($match->status =='cancelled'){
                            $status_a = 'cancelled';
                            $_profit = 0.00;
                        }
                        $user_a->push([
                            'id' => $bet->user->id,
                            'name' => $bet->user->name,
                            'amount' => $bet->amount,
                            'profit' => $_profit,
                        ]);
                        
                    }
                    $team_a = collect([
                        'users' => $user_a,
                        'total_bettors' => $total_team_a_bettors,
                        'total_bet' => $total_team_a_bets,
                        'average_bet' => $total_team_a_bettors != 0 ? ($total_team_a_bets/$total_team_a_bettors) : 0.00,
                        'total_profit' => $total_team_a_profit,
                        'status' =>  $status_a,
                        'id' => $match->team_a,
                        'name' => $match->teamA->name,
                    ]);
            
                    if($match->team_b == $match->team_winner && $match->status =='settled'){
                        $status_b = 'win';
                    }elseif ($match->status =='draw'){
                        $status_b = 'draw';
                    }elseif ($match->team_b != $match->team_winner && $match->status =='settled') {
                        $status_b = 'lost';
                    }elseif ($match->status =='open' || $match->status =='ongoing') {
                        $status_b = 'ongoing';
                    }elseif ($match->status =='cancelled'){
                        $status_b = 'cancelled';
                    }
            
                    foreach ($teamBbets as $bet) {
            
                        $profit = $bet->gains;
                        if($bet->team_id == $match->team_winner && $match->status =='settled'){
                            $total_team_b_profit+=$profit;
                            $status_b = 'win';
                            $_profit = $profit;
                        }elseif ($match->status =='draw'){
                            $status_b = 'draw';
                            $_profit = 0.00;
                        }elseif ($bet->team_id != $match->team_winner && $match->status =='settled') {
                            $total_team_b_profit+=$bet->amount;
                            $status_b = 'lost';
                            $_profit = $bet->amount;
                        }elseif ($match->status =='open' || $match->status =='ongoing') {
                            $status_b = 'ongoing';
                            $_profit = 0.00;
                        }elseif ($match->status =='cancelled'){
                            $status_b = 'cancelled';
                            $_profit = 0.00;
                        }
                        $user_b->push([
                            'id' => $bet->user->id,
                            'name' => $bet->user->name,
                            'amount' => $bet->amount,
                            'profit' => $_profit,
                        ]);
            
                    }
            
                    $team_b = collect([
                        'users' => $user_b,
                        'total_bettors' => $total_team_b_bettors,
                        'total_bet' => $total_team_b_bets,
                        'average_bet' => $total_team_b_bettors != 0 ? ($total_team_b_bets/$total_team_b_bettors) : 0.00,
                        'total_profit' => $total_team_b_profit,
                        'status' =>  $status_b,
                        'id' => $match->team_b,
                        'name' => $match->teamB->name,
                    ]);
                    
                    switch ($match->status) {
                        case 'draw':
                            $collected_fees = 0.00;
                            $total_payout = 0.00;
                            break;
                        case 'open':
                            $collected_fees = 0.00;
                            $total_payout = 0.00;
                            break;
                        case 'settled':
                            $collected_fees = $total_bets*$match->fee;
                            $total_payout = $total_bets-$collected_fees;
                            break;
                        case 'ongoing':
                            $collected_fees = $total_bets*$match->fee;
                            $total_payout = $total_bets-$collected_fees;
                            break;
                        case 'cancelled':
                            $collected_fees = 0.00;
                            $total_payout = 0.00;
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    $data = collect([
                        'team_a' => $team_a,
                        'team_b' => $team_b,
                    ]);

                    $total_circulating_credits = number_format(str_replace(",","",getCirculatingCredits()),2,'.','');

                    $team_a_ratio =  $total_team_a_bets > 0 ? $total_bets / $total_team_a_bets * (1 - $match->fee) : 0;
                    $team_b_ratio = $total_team_b_bets > 0 ? $total_bets / $total_team_b_bets * (1 - $match->fee) : 0;

                    $team_a_ratio_off = bcdiv($team_a_ratio, 1 ,2); 
                    $team_b_ratio_off = bcdiv($team_b_ratio, 1 ,2);
                    
                    $round_off_earnings = 0;

                    if($match->status =='settled'){
                        $winner_total_bets =  $match->team_winner  == $match->team_a ? $total_team_a_bets : $total_team_b_bets;
                        $winner_ratio = $match->team_winner  == $match->team_a ? $team_a_ratio : $team_b_ratio;
                        $winner_ratio_off = $match->team_winner  == $match->team_a ? $team_a_ratio_off : $team_b_ratio_off;

                        $round_off_earnings = ($winner_total_bets * $winner_ratio) - ($winner_total_bets * $winner_ratio_off);
                    }

                
                    Log::info('Winner ratio: ' . $winner_ratio . ' - ' . ' Winner ratio off : ' . $winner_ratio_off . ' - amount : ' . $winner_total_bets . ' - earn: ' . $round_off_earnings);

                    MatchReport::create(
                        [
                            'id' => $match->id,
                            'league_id' => $match->league_id,
                            'status' => $match->status,
                            'name' => $match->name,
                            'label' => $match->label,
                            'settled_by' =>$this->data['settled_by'],
                            'data' => $data->toJson(),
                            'total_bettors' => ($total_team_b_bettors+$total_team_a_bettors),
                            'total_match_bet' => $total_bets,
                            'average_match_bet' => $total_team_b_bettors != 0 || $total_team_a_bettors != 0 ? ($total_bets/($total_team_b_bettors+$total_team_a_bettors)) : 0.00,
                            'match_fee' => $match->fee,
                            'total_fees_collected' => $collected_fees,
                            'total_payout' => $total_payout,
                            'circulating_credits_before_settled' => number_format(str_replace(",","",$this->data['total_circulating_credits']),2,'.',''),
                            'circulating_credits_after_settled' => $total_circulating_credits,
                            'round_off_earnings' => $round_off_earnings,
                        ]
                    );

                    $_match = MatchReport::find($match->id);
                    
                    $fee = new Fee;
                    $fee->meta_key = 'match';
                    $fee->meta_value = $match->id;
                    $fee->percent = $match->fee;
                    $fee->collected =  $collected_fees;
                    $fee->save();
                }
                    
                // Log::info('Testing ' . $_match->id . ' ' . json_encode($_match));
                Log::info('Sending '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.$_match->created_at.' match report | ID: '.$_match->id.'...');

                $excelFile = \Excel::create('Match Report of '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.date('Y-m-d',strtotime($_match->created_at)), function($excel) use($_match) {
                    $excel->sheet('Overview', function($sheet) use($_match) {
                        $sheet->loadView('emails.match-report', array('match' => $_match));
                    });
                });
                        
                \Mail::raw('See attached excel file for the report. | Match Name: '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' | Match ID : '.$_match->id.' | Date: '.$_match->created_at.' | Settled By: '.$_match->settledBy->name, function($message) use ($excelFile,$_match) {
                    $message->from('admin@2ez.bet', '2ez.bet Admin');
                    $message->to('reports@2ez.bet')
                    ->cc('brandnewbien@gmail.com')
                    ->cc('south.2ez@gmail.com')
                    ->subject('Match Report of '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.$_match->created_at);
                    $message->attach($excelFile->store("xlsx", false, true)['full']);
                });
        
                Log::info('Sent match Report of '.$_match->data->team_a->name.' vs '.$_match->data->team_b->name.' - '.$_match->created_at);
                break;
            case 'daily':
                break;
            default:
                # code...
                break;
        }
    }
}
