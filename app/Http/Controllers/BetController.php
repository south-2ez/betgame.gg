<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\BetRepository;
use Carbon;
use Cache;

class BetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function tournamentBettings(\App\League $league)
    {
        if($league->exists) {
            $user_bets = \App\Bet::tournament($league->id)
                    ->where('user_id', \Auth::user()->id)->get();
            return view('tournament', compact('league', 'user_bets'));
        }
    }
    
    public function addTournamentBet(Request $request)
    {

        \DB::setDefaultConnection('mysql_transaction');
        $user_id = \Auth()->id();
        $cacheKey = 'cashoutDepositBetRequest_' . $user_id;

        if ( !Cache::store('redis_svr03')->has($cacheKey) ) {
            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);

            $rules = [
                'bet_amount' => 'required|integer|min:100',
                'leagueid' => 'required|exists:leagues,id',
                'teamid' => 'required'
            ];
            $validation = \Validator::make($request->all(), $rules);
            if ($validation->passes()) {
                $user = \Auth::user();
                if ($request->bet_amount <= $user->credits) {
                    $league = \App\League::find($request->leagueid);
                    if ($league->betting_status) {
                        if ($league->teams->contains($request->teamid)) {
                            $team = $league->teams->where('id', $request->teamid)->first();
                            if (hasMatchManagementAccess(\Auth::user())) {
                                $bet_ctr = \App\Bet::tournament($league->id)
                                                ->where('team_id', $team->id)
                                                ->where('user_id', \Auth::user()->id)->count();
                                if ($bet_ctr > 0) {

                                    Cache::store('redis_svr03')->forget($cacheKey);
                                    return [
                                        'success' => false,
                                        'errors' => [
                                            'bet_status' => ['The selected team bet already exists.']
                                        ]
                                    ];
                                } else {
                                    $newbet = new \App\Bet;
                                    $newbet->user_id = \Auth::user()->id;
                                    $newbet->type = 'tournament';
                                    $newbet->team_id = $team->id;
                                    $newbet->amount = $request->bet_amount;
                                    $newbet->league_id = $league->id;
                                    $newbet->match_id = null;
                                    $newbet->save();

                                    // $user->credits -= $request->bet_amount;
                                    // $user->save();
                                    
                                    $user->decrement('credits', $request->bet_amount); 
                                    Cache::store('redis_svr03')->forget($cacheKey);                               
                                    return ['success' => true];
                                }
                            } else {
                                if ($team->pivot->is_favorite && $request->bet_amount < $league->favorites_minimum) {
                                    Cache::store('redis_svr03')->forget($cacheKey);
                                    return [
                                        'success' => false,
                                        'errors' => [
                                            'bet_amount' => ['Bet amount should be at least ' . $league->favorites_minimum . ' for a favorite team.']
                                        ]
                                    ];
                                } else {
                                    $bet_ctr = \App\Bet::tournament($league->id)
                                                    ->where('team_id', $team->id)
                                                    ->where('user_id', \Auth::user()->id)->count();
                                    if ($bet_ctr > 0) {
                                        Cache::store('redis_svr03')->forget($cacheKey);
                                        return [
                                            'success' => false,
                                            'errors' => [
                                                'bet_status' => ['The selected team bet already exists.']
                                            ]
                                        ];
                                    } else {
                                        $newbet = new \App\Bet;
                                        $newbet->user_id = \Auth::user()->id;
                                        $newbet->type = 'tournament';
                                        $newbet->team_id = $team->id;
                                        $newbet->amount = $request->bet_amount;
                                        $newbet->league_id = $league->id;
                                        $newbet->match_id = null;
                                        $newbet->save();

                                        // $user->credits -= $request->bet_amount;
                                        // $user->save();
                                        $user->decrement('credits', $request->bet_amount);
                                        Cache::store('redis_svr03')->forget($cacheKey);
                                        return ['success' => true];
                                    }
                                }
                            }
                        } else {
                            Cache::store('redis_svr03')->forget($cacheKey);
                            return [
                                'success' => false,
                                'errors' => [
                                    'bet_status' => ['The selected team is invalid.']
                                ]
                            ];
                        }
                    } else {
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return [
                            'success' => false,
                            'errors' => [
                                'bet_status' => ['Betting is now closed for this Tournament.']
                            ]
                        ];
                    }
                } else {
                    Cache::store('redis_svr03')->forget($cacheKey);
                    return [
                        'success' => false,
                        'errors' => [
                            'credits' => ['Insufficient Ez credits.<br/>Total available credits: ' . $user->credits]
                        ]
                    ];
                }
            } else {

                Cache::store('redis_svr03')->forget($cacheKey);
                return [
                    'success' => false,
                    'errors' => $validation->errors()
                ];
            }


        }else{
            return [
                'success' => false, 
                'error' => 'Place bet failed. Please try again. Error Code: CO0820',
            ];               
        }

    }
    
    public function updateTournamentBet(Request $request)
    {

        \DB::setDefaultConnection('mysql_transaction');
        $user_id = \Auth()->id();
        $cacheKey = 'cashoutDepositBetRequest_' . $user_id;

        if ( !Cache::store('redis_svr03')->has($cacheKey) ) {

            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);

            $rules = [
                'bet_amount' => 'required|integer|min:1',
                'leagueid' => 'required|exists:leagues,id',
                'bet_id' => 'required'
            ];
            
            $validation = \Validator::make($request->all(), $rules);
            if ($validation->passes()) {
                $user = \Auth::user();
                if ($request->bet_amount <= $user->credits) {
                    $league = \App\League::find($request->leagueid);
                    $currentBet = \App\Bet::tournament($league->id)
                                    ->where('id', $request->bet_id)
                                    ->where('user_id', $user->id)->first();
                    if ($currentBet) {
                        if ($league->betting_status) {
                            $currentBet->amount += $request->bet_amount;
                            $currentBet->save();
                            
                            // Add to bet history
                            \App\BetHistory::create([
                                'type' => 'update',
                                'bet_id' => $currentBet->id,
                                'amount' => $request->bet_amount,
                                'user_id' => $user->id,
                                'curr_credits' => $user->credits
                            ]);

                            // $user->credits -= $request->bet_amount;
                            // $user->save();
                            $user->decrement('credits', $request->bet_amount);
                            Cache::store('redis_svr03')->forget($cacheKey);
                            return ['success' => true];
                        } else {
                            Cache::store('redis_svr03')->forget($cacheKey);
                            return [
                                'success' => false,
                                'errors' => [
                                    'bet_status' => ['Betting is now closed for this Tournament.']
                                ]
                            ];
                        }
                    } else {
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return [
                            'success' => false,
                            'errors' => [
                                'bet_status' => ['This bet does not exists!']
                            ]
                        ];
                    }
                } else {
                    Cache::store('redis_svr03')->forget($cacheKey);
                    return [
                        'success' => false,
                        'errors' => [
                            'credits' => ['Insufficient Ez credits.<br/>Total available credits: ' . $user->credits]
                        ]
                    ];
                }
            } else {
                Cache::store('redis_svr03')->forget($cacheKey);
                return [
                    'success' => false,
                    'errors' => $validation->errors()
                ];
            }

        }else{
            return [
                'success' => false, 
                'error' => 'Place bet failed. Please try again. Error Code: CO0820',
            ];   
        }


    }
    
    public function setTournamentBet(Request $request)
    {
        $rules = ['bet_amount' => 'required|integer|min:100'];
        
        $validation = \Validator::make($request->all(), $rules);
        if ($validation->passes()) {
            $user = \Auth::user();
            $team = \App\Team::find($request->teamid);
            if ($team) {
                $match = \App\Match::find($request->matchid);
                if($match && $match->status != 'open') {
                    return [
                        'success' => false,
                        'errors' => [
                            'bet_status' => ['The selected match is already closed']
                        ]
                    ];
                } else {
                    if (($team->is_favorite && !$request->matchid)&& $request->bet_amount < 500) {
                        return [
                            'success' => false,
                            'errors' => [
                                'bet_amount' => ['Bet amount should be greater than 500 for a favorite team.']
                            ]
                        ];
                    } else {
                        if ($this->isAllowedMoreBets($user, $request->matchid)) {
                            $bet = $request->matchid ? \App\Bet::where('team_id', $request->teamid)
                                            ->where('user_id', $user->id)->where('match_id', $request->matchid)->first() : 
                                \App\Bet::where('team_id', $request->teamid)
                                    ->where('user_id', $user->id)->where('type', 'tournament')->first();
                            if ($bet) {
                                $total_credits = $user->credits + $bet->amount;
                                if ($request->bet_amount <= $total_credits) {
                                    $bet->amount = $request->bet_amount;
                                    $bet->save();

                                    // $user->credits = ($total_credits - $request->bet_amount);
                                    // $user->save();
                                    $user->decrement('credits', $request->bet_amount);
                                } else {
                                    return [
                                        'success' => false,
                                        'errors' => [
                                            'credits' => ['Insufficient Ez credits.<br/>Total available credits: ' . $total_credits]
                                        ]
                                    ];
                                }
                            } else {
                                if ($request->bet_amount <= $user->credits) {
                                    $newbet = \App\Bet::create([
                                        'user_id' => $user->id,
                                        'type' => $request->matchid ? 'matches' : 'tournament',
                                        'team_id' => $team->id,
                                        'amount' => $request->bet_amount,
                                    ]);
                                    $newbet->match_id = $request->matchid ? $request->matchid : null;
                                    $newbet->save();

                                    // $user->credits -= $request->bet_amount;
                                    // $user->save();
                                    $user->decrement('credits', $request->bet_amount);
                                } else {
                                    return [
                                        'success' => false,
                                        'errors' => [
                                            'team' => ['Insufficient Ez credits.<br/>You currently have: ' . $user->credits]
                                        ]
                                    ];
                                }
                            }
                            return ['success' => true];
                        } else {
                            return [
                                'success' => false,
                                'errors' => [
                                    'bets' => ['You have exceeded the allowed number of bets.<br/>Total number of bets: ' . $user->bets->where('type', 'tournament')->count()]
                                ]
                            ];
                        }
                    }
                }
            } else {
                return [
                    'success' => false,
                    'errors' => [
                        'team' => ['Invalid Team entered']
                    ]
                ];
            }
        } else {
            return [
                'success' => false,
                'errors' => $validation->errors()
            ];
        }
    }
    
    public function addMatchBet(Request $request)
    {

        $isMatchBettingAllowed = !empty($request->matchid) ? isMatchBettingAllowed($request->matchid) : false;
        if(!$isMatchBettingAllowed){
            return [
                'success' => false,
                'errors' => [
                    'match' => ['Match has already started']
                ]
            ];   
        }
    

        \DB::setDefaultConnection('mysql_transaction');
        $user_id = \Auth()->id();
        $cacheKey = 'cashoutDepositBetRequest_' . $user_id;
        
        if ( !Cache::store('redis_svr03')->has($cacheKey) ) {

            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);

            $rules = [
                'matchid' => 'required|integer|exists:matches,id',
                'teamid' => 'required|integer|exists:teams,id',
                'bet_amount' => 'required|integer|min:100'
            ];
            
            $validation = \Validator::make($request->all(), $rules);
            if ($validation->passes()) { //if all required fields are being passed by the requesat

                $user = \Auth::user(); //get currently authenticated user
                $match = \App\Match::find($request->matchid); //getting the match details
                $team = \App\Team::find($request->teamid); //getting team details that the user bet on
                if(!in_array($team->id, [$match->team_a, $match->team_b, $match->team_c])) { //checks if team is still on the match

                    Cache::store('redis_svr03')->forget($cacheKey);
                    return [
                        'success' => false,
                        'errors' => [
                            'bet' => ['Match teams have been updated! Please refresh page and try again.']
                        ]
                    ];
                }
                
                if($match && $match->status == 'open' ) { //check if match is not null and match is still open

                    $betCount = $user->bets->where('match_id', $request->matchid)->count(); //get count of bets betted by user

                        //ID exception of boy next door to control last minute bettors
                        if($match && $match->isClosing(0)) {
                            if($user->id == 1066){
                                $betCount = $user->bets->where('match_id', $request->matchid)->count();
                            }else{
                                Cache::store('redis_svr03')->forget($cacheKey);
                                return [
                                    'success' => false,
                                    'errors' => [
                                        'bet' => ['Could no longer bet. This match is now starting!']
                                    ]
                                ];
                            }
                        }

                    if ($betCount && !hasMatchManagementAccess(\Auth::user())) {
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return [
                            'success' => false,
                            'errors' => [
                                'bet' => ['This bet already exists!']
                            ]
                        ];
                    }
                    if ($request->bet_amount <= $user->credits) {
                        
                        // Delete existing bets with same match_id
                        if(!hasMatchManagementAccess(\Auth::user())) {
                            foreach($user->bets->where('match_id', $match->id) as $bet) {
                                $bet->delete();
                            }
                        }
                        
                        if ($user->bets->where('match_id', $match->id)->count() <= 0 || hasMatchManagementAccess(\Auth::user())) {
                            // $newbet = new \App\Bet;
                            // $newbet->user_id = $user->id;
                            // $newbet->type = 'matches';
                            // $newbet->team_id = $team->id;
                            // $newbet->amount = $request->bet_amount;
                            // $newbet->match_id = $match->id;
                            // $newbet->league_id = $match->league_id;
                            // $newbet->save();
                            // $created_at = Carbon::now()->format('Y-m-d H:i:s');

                            if(!hasMatchManagementAccess(\Auth::user())) {
                                $newbet = \App\Bet::firstOrNew(
                                        [ 
                                            'user_id' => $user->id,
                                            'type' => 'matches',
                                            'match_id' => $match->id,
                                            'amount' => $request->bet_amount,
                                            // 'created_at' => $created_at
                                        ],
                                        [
                                            'team_id' => $team->id,
                                            'league_id' => $match->league_id
                                        ]
                                    );  

                                    if($newbet->exists){

                                    }else{
                                        $newbet->save();
                                        
                                        // $user->credits -= $request->bet_amount;
                                        // $user->save();
                                        $user->decrement('credits', $request->bet_amount);
                                        $user->touch();
                                    }
                            }else{
                                $newbet = new \App\Bet;
                                $newbet->user_id = $user->id;
                                $newbet->type = 'matches';
                                $newbet->team_id = $team->id;
                                $newbet->amount = $request->bet_amount;
                                $newbet->match_id = $match->id;
                                $newbet->league_id = $match->league_id;
                                $newbet->save();
    
                                $user->decrement('credits', $request->bet_amount);
                                $user->touch();                   
                            }
    

                            // $newbet->team_id = $team->id;
                            // $newbet->league_id = $match->league_id;
                            //$newbet->save();


                            

                            if ($user->bets->where('match_id', $match->id)->count() > 1 && !hasMatchManagementAccess(\Auth::user())) {
                                
                                // Delete existing bets with same match_id
                                foreach ($user->bets->where('match_id', $match->id) as $bet) {
                                    $bet->delete();
                                }
                                
                                Cache::store('redis_svr03')->forget($cacheKey);
                                return [
                                    'success' => false,
                                    'errors' => [
                                        'bet' => ['There seems to be a problem, please try again!']
                                    ]
                                ];
                            } else{

                                //if no problem with betting then check if initial odds is enabled, then use bot betting
                                if($match->is_initial_odds_enabled == 1){

                                    $bot_bet_data = [
                                        'match' => $match,
                                        'bet_id' => $newbet->id,
                                        'bet_amount' => $request->bet_amount,
                                        'teamid' => $request->teamid
                                    ];
                                    $bet_repository = new BetRepository;
                                    $bet_repository->insertBetBot($bot_bet_data);


                                }
                                //then let bot bet   
                                Cache::store('redis_svr03')->forget($cacheKey);
                                return ['success' => true];
                            }
                            
                            
                        } else {
                            Cache::store('redis_svr03')->forget($cacheKey);
                            return [
                                'success' => false,
                                'errors' => [
                                    'bet' => ['This bet already exists!']
                                ]
                            ];
                        }
                    } else {
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return [
                            'success' => false,
                            'errors' => [
                                'credits' => ['Insufficient Ez credits.<br/>Total available credits: ' . $user->credits]
                            ]
                        ];
                    }
                } else { //else match already started, and user can't bet anymore
                    Cache::store('redis_svr03')->forget($cacheKey);
                    return [
                        'success' => false,
                        'errors' => [
                            'match' => ['Match has already started']
                        ]
                    ];
                }
            } else {
                Cache::store('redis_svr03')->forget($cacheKey);
                return [
                    'success' => false,
                    'errors' => $validation->errors()
                ];
            }
        }else{

            return [
                'success' => false, 
                'error' => 'Place bet failed. Please try again. Error Code: CO0820',
            ];   
        }


    }
    
    
    public function updateMatchBet(Request $request)
    {

        $isMatchBettingAllowed = !empty($request->matchid) ? isMatchBettingAllowed($request->matchid) : false;
        
        if(!$isMatchBettingAllowed){
            return [
                'success' => false,
                'errors' => [
                    'match' => ['Match has already started']
                ]
            ];   
        }

        $validation = \Validator::make($request->all(), [
            'bet_amount' => 'required|integer|min:1',
            'teamid' => 'required',
            'betid' => 'required'
        ]);

        if ($validation->passes()) {
            $user = \Auth::user();
            $team = \App\Team::find($request->teamid);
            $bet = \App\Bet::find($request->betid);
            $match = $bet->match;
            
            if(!in_array($team->id, [$match->team_a, $match->team_b, $match->team_c])) {
                return [
                    'success' => false,
                    'errors' => [
                        'bet' => ['Match teams have been updated! Please refresh page and try again.']
                    ]
                ];
            }
            
            if($match && $match->status == 'open') {
                
                //ID exception of boy next door to control last minute bettors
                if($match && $match->isClosing(0)) {

                    if($user->id == 1066){
                        $betCount = $user->bets->where('match_id', $request->matchid)->count();

                    }else{
                        return [
                            'success' => false,
                            'errors' => [ 'bet' => ['Could no longer bet. This match is now starting!'] ]
                        ];
                    }
                }
                
                if ($request->bet_amount <= $user->credits) {
                    $bet->amount += $request->bet_amount;
                    $bet->save();
                    
                    // // Add to bet history
                    // \App\BetHistory::create([
                    //     'type' => 'update',
                    //     'match_id' => $bet->match_id,
                    //     'bet_id' => $bet->id,
                    //     'amount' => $request->bet_amount,
                    //     'user_id' => $user->id,
                    //     'curr_credits' => $user->credits
                    // ]);

                    // $user->credits -= $request->bet_amount;
                    // $user->save();
                    $user->decrement('credits', $request->bet_amount);
                    $user->touch();

                    if($match->is_initial_odds_enabled == 1){

                        $data = [
                            'match' => $match,
                            'bet_id' => $bet->id,
                            'bet_amount' => $request->bet_amount,
                            'teamid' => $request->teamid
                        ];

                        // $this->_updateBotBet($data);
                        $bet_repository = new BetRepository;
                        $bet_repository->updateBetBot($data);
                    }
                    
                    return ['success' => true];
                } else {
                    return [
                        'success' => false,
                        'errors' => [
                            'credits' => ['Insufficient Ez credits.<br/>Total available credits: ' . $user->credits]
                        ]
                    ];
                }

            } else {
                return [
                    'success' => false,
                    'errors' => [
                        'match' => ['Match has already started.']
                    ]
                ];
            }
        } else {
            return [
                'success' => false,
                'errors' => $validation->errors()
            ];
        }
    }
    
    public function switchTeamBet(Request $request)
    {
        // $validation = \Validator::make($request->all(), [
        //     'teamid' => 'required',
        //     'betid' => 'required'
        // ]);
        
        // if ($validation->passes()) {
        //     $bet = \App\Bet::find($request->betid);
        //     if ($bet && $bet->match->status == 'open') {
        //         $bet->team_id = $request->teamid;
        //         $bet->save();
        //     } else {
        //         return [
        //             'success' => false,
        //             'error' => 'This match/bet does not exists or already started!'
        //         ];
        //     }
        //     return ['success' => true];
        // } else {
        //     return [
        //         'success' => false,
        //         'errors' => $validation->errors()
        //     ];
        // }
        abort(404);
    }
    
    public function getUserBets(Request $request) 
    {
        $user = \Auth::user();
        $data = $user->bets()->with(['team', 'match' => function($query) {
            $query->select('id','name', 'status', 'team_winner');
        }, 'league' => function($query) {
            $query->select('id','name', 'status', 'betting_status', 'league_winner');
        }]);
        return \Datatables::of($data)
                ->addColumn('potential_winnings', function (\App\Bet $bet) use($user) {
                    if($bet->match) {

                        switch($bet->match->status){
                            case 'cancelled': 
                            case 'draw':
                                    return 0;
    
                            case 'ongoing': 
                            case 'open': 
                            case 'settled':

                                $_matchRatio = $bet->ratio ? $bet->ratio : $bet->team->matchRatio($bet->match->id);
                                return $_matchRatio * $bet->amount;

                            default: 
                                return 0;
                        
                        }

                    } else {

                        $leagueId = $bet->league_id;
                        $team_id = $bet->team_id;

                        $leagueTeamsRatiosCacheKey = md5('for-home-page-active-leagues-ratio');                
                        $leaguesCachedRatios = Cache::get($leagueTeamsRatiosCacheKey); //get cached tournament teams ratio 

                        $cachedRatio = ( !empty($leaguesCachedRatios) && !empty($leaguesCachedRatios[$leagueId]) ) ? $leaguesCachedRatios[$leagueId] : null;
                        $teamRatio =  !empty($cachedRatio) ? $cachedRatio[$team_id] : null;

                        if(!empty($teamRatio)){
                            return $bet->amount * $teamRatio->teamRatio;
                        }else{
                            return potentialTournamentWinningPerUserPerTeam($bet->league_id, $bet->team_id, $user->id);
                        }

                        
                    }
                })->make(true);
    }
    
    public function cancelTournamentBet(Request $request)
    {

         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */
        $user_id = \Auth()->id();

        $cacheKey = 'cashoutDepositBetRequest_' . $user_id;

        if ( !Cache::store('redis_svr03')->has($cacheKey)) {

            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);

            $bet = \App\Bet::tournament($request->league_id)
                    ->where('user_id', \Auth::user()->id)
                    ->where('id', $request->bet_id)->first();
            
            if($bet) {
                $league = \App\League::find($request->league_id);
                if($league->betting_status == 1) {
                    $bet->delete();

                    Cache::store('redis_svr03')->forget($cacheKey);
                    return ['success' => true];

                } else {

                    Cache::store('redis_svr03')->forget($cacheKey);
                    return ['success' => false, 'error' => 'Betting is already closed!'];
                }
            } else {
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => false];
            }


        }else{
            return [
                'success' => false, 
                'error' => 'Bet cancellation failed. Please try again. Error Code: CO0820',
            ];     
        }


    }
    
    public function cancelMatchBet(Request $request)
    {

         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */
        $user_id = \Auth()->id();

        $cacheKey = 'cashoutDepositBetRequest_' . $user_id;

        if ( !Cache::store('redis_svr03')->has($cacheKey)) {

            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);

            $bet = \App\Bet::where('id', $request->betid)
                    ->where('user_id', \Auth::user()->id)
                    ->first();

            if ($bet) {
                if ($bet->match && $bet->match->status == 'open') {
                    if ($bet->match->isClosing() && $user_id != 1066){
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return ['error' => 'Cannot cancel bet, Match is about to start.'];
                    }else{
                        \App\Bet::find($request->betid)->delete();

                        if($bet->match->is_initial_odds_enabled == 1){ //we also cancel the bet of bot against this bet id
                            $botBets = \App\Bet::where('bot_bet_against_bet_id', $request->betid)->get();
                            if(!empty($botBets)){
                                foreach($botBets as $botBet){
                                    $botBet->delete();
                                }
                            }
                        }                   
                    }
                } else{

                    Cache::store('redis_svr03')->forget($cacheKey);
                    return ['error' => 'Cannot cancel bet, Match has already started!'];
                }
                    
            } else {
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['error' => 'This bet DOES not exist! Incident has been reported to Admins.'];
            }

            Cache::store('redis_svr03')->forget($cacheKey);
            return ['success' => true];   
            
        }else{

            return [
                'success' => false, 
                'error' => 'Bet cancellation failed. Please try again. Error Code: CO0820',
            ];   
        }

    }
    
    public function getPossibleWinningPerUser(Request $request)
    {
        $match = \App\Match::find($request->matchid);
        if (hasMatchManagementAccess(\Auth::user()) && $request->betid) {
            $query = \DB::table('bets')->where('match_id', $request->matchid)->where('id', '!=', $request->betid)
                ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                ->groupBy('match_id', 'team_id')->get();
            $total_bets = $query->sum('total_bets') + $request->amount;
            $team_bets = $query->where('team_id', $request->teamid)->sum('total_bets') + $request->amount;
            $team_ratio = $team_bets ? $total_bets / $team_bets * (1 - $match->fee) : 0;

            $total_team_payout = $team_bets * $team_ratio;
            $potential_winning = $team_bets ? ($request->amount / $team_bets) * $total_team_payout : 0;
            return [
                'success' => true,
                'ratio' => $team_ratio,
                'amount' => $potential_winning
            ];
        } else {
            $query = \DB::table('bets')->where('match_id', $request->matchid)
                ->selectRaw("COUNT(id) AS bet_count, SUM(amount) AS total_bets, team_id, match_id")
                ->groupBy('match_id', 'team_id')->get();
            $total_bets = $query->sum('total_bets') + $request->amount;
            $team_bets = $query->where('team_id', $request->teamid)->sum('total_bets') + $request->amount;
            $team_ratio = $team_bets ? $total_bets / $team_bets * (1 - $match->fee) : 0;

            $user_bet = hasMatchManagementAccess(\Auth::user()) ? \Auth::user()->getMatchBetAmount($request->matchid, $request->amount, $request->teamid) :
                    \Auth::user()->getMatchBetAmount($request->matchid, $request->amount);
            $total_team_payout = $team_bets * $team_ratio;
            $potential_winning = $team_bets ? ($user_bet / $team_bets) * $total_team_payout : 0;
            return [
                'success' => true,
                'ratio' => $team_ratio,
                'amount' => $potential_winning
            ];
        }
    }
    
    public function getTournamentPossibleWinningPerUser(Request $request)
    {
        return [
            'success' => true,
            'ratio' => tournamentRatioPerTeam($request->leagueid, $request->teamid, $request->amount),
            'amount' => potentialTournamentWinningPerUserPerTeam($request->leagueid, $request->teamid, \Auth::user()->id, $request->amount)
        ];
    }
    
    private function isAllowedMoreBets(\App\User $user, $match)
    {
        if(hasMatchManagementAccess(\Auth::user()))
            return true;
        else {
            if($match)
                return true;
            else {
                if($user->bets->where('type', 'tournament')->count() < 6)
                    return true;
                else
                    return false;
            }
        }
    }

}
