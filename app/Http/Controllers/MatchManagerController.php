<?php

namespace App\Http\Controllers;

use App\Jobs\SendMatchReport;
use App\Jobs\SendOutrightReport;
use App\Jobs\SetMatchBetsRatio;
use App\Jobs\GenerateMatchBetsAffliatesCommissions;
use App\Jobs\GenerateTournamentBetsAffliatesCommissions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;
use App\League;
use App\Fee;
use App\Repositories\UpdatedMatchOddsLogsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('matchmanager');
    }
    
    public function index() {
        
        $teams = \App\Team::all();
        $users = \App\User::all();
        $match = \App\Match::all();
        $leagues = \App\League::ongoing()->orderBy('created_at', 'desc')->get();
        $types = \App\GameType::all();
        return view('admin.matchmanager', compact('teams', 'leagues', 'match', 'types'));
    }
    
    public function editMatchManagerBet(Request $request) {
        $rules = [
            'betid' => 'required'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $bet = \App\Bet::find($request->betid);
            $user = \Auth::user();

            if($bet) {

                $newbet = new \App\Bet;
                $newbet->user_id = $user->id;
                $newbet->type = $bet->type;
                $newbet->team_id =  $bet->team_id;
                $newbet->amount = $request->bet_amount;
                $newbet->match_id =  $bet->match_id;
                $newbet->league_id = $bet->league_id;
                
                $newbet->save();
                $bet->delete();

                $user->decrement('credits', $request->bet_amount);
                $user->touch();                

                return ['success' => true];
            }
        } else
            return ['success' => false, 'errors' => $validator->errors()];
    }

    public function getAllTeams() {
        return Datatables::of(\App\Team::all())->make(true);
    }
    
    public function showRawTeamsType(League $league) {
        if ($league->exists) {
            $league_teams = $league->teams;
            $teams = $league_teams->where('type', 'tbd')->count() ? $league_teams : 
                    $league_teams->merge(\App\Team::type('tbd')->get());
            return $league->exists ? $teams : [];
        }
    }
    
    public function cancelAdminBet(Request $request) {
        $bet = \App\Bet::find($request->betid);
        if ($bet) {
            $bet->delete();

            if($bet->match->is_initial_odds_enabled == 1){ //we also cancel the bet of bot against this bet id
                $botBets = \App\Bet::where('bot_bet_against_bet_id', $request->betid)->get();
                if(!empty($botBets)){
                    foreach($botBets as $botBet){
                        $botBet->delete();
                    }
                }
            }
            
            return ['success' => true];
        } else
            return ['error' => 'This bet DOES not exist! Incident has been reported to Admins.'];
    }

    public function removeAdminBets(Request $request)
    {
        /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */
        
        $match_id = $request->match_id;
        if(!empty($match_id)){
            $adminMMUsersIds = getAdminMatchManagersUserIds();
            //first return their betted credits first
            $bets = \App\Bet::where('match_id', $match_id)->whereIn('user_id', $adminMMUsersIds)->get();

            foreach($bets as $bet){
                $bet->delete();
            }

            return ['success' => true, 'message' =>   'Admin bets deleted.'  ];

        }else{
            return ['success' => false, 'message' => 'Match not found.'];
        }

        
    }

    public function getMatchSubDetails(Request $request)
    {
        $match_id = $request->match_id ?? 0;

        $match = \App\Match::find($match_id);
        $match->makeVisible([
            'team_a_threshold_percent',
            'team_a_max_threshold_percent',
            'team_b_threshold_percent',
            'team_b_max_threshold_percent',
        ]);

        if(!empty($match) ){
            if($match->type == 'main'){

                $match->load('teamA','teamB');
                $where = [
                    'main_match' => $match_id,
                    'type' => 'sub',
                ];

                $subMatches = \App\Match::where($where)
                                ->with('teamA','teamB')
                                ->whereIn('game_grp',[0,1])
                                ->get()
                                ->makeVisible([
                                    'team_a_threshold_percent',
                                    'team_a_max_threshold_percent',
                                    'team_b_threshold_percent',
                                    'team_b_max_threshold_percent',
                                ])
                                ->toArray();

                return [
                    'success' => true,
                    'message' => 'Match found.',
                    'matches' => array_merge( [$match], $subMatches)
                ];

            }else if($match->sub_type == 'main'){

                $match->load('teamA','teamB');
                $where = [
                    'main_match' => $match->main_match,
                    'game_grp' => $match->game_grp,
                    'type' => 'sub',
                ];

                $subMatches = \App\Match::where($where)
                                ->with('teamA','teamB')                              
                                ->get()
                                ->makeVisible([
                                    'team_a_threshold_percent',
                                    'team_a_max_threshold_percent',
                                    'team_b_threshold_percent',
                                    'team_b_max_threshold_percent',
                                ])                                  
                                ->toArray();

                return [
                    'success' => true,
                    'message' => 'Match found.',
                    'matches' => $subMatches
                ];

            }


        }else{
            return [
                'success' => false,
                'message' => 'Match not found.'
            ];
        }
    }

    public function updateMoreInfoLink(Request $request)
    {
        $match_id = $request->match_id ? $request->match_id : 0;
        $match = \App\Match::find($match_id);

        if(!empty($match)){
            $more_info_link = $request->link;
            $match->more_info_link = $more_info_link;
            $match->save();
            return ['success' => true, 'message' => 'Match Why/More Information link updated.'];
        }else{
            return ['success' => false, 'message' => 'Match not found.'];
        }
        
    }

    public function updateStreamLinks(Request $request)
    {
        $match_id = $request->match_id ? $request->match_id : 0;
        $match = \App\Match::find($match_id);

        if(!empty($match)){
            if($match->type != 'main'){
                return ['success' => false, 'message' => 'Cannot update stream links via sub-match.'];
            }

            $match->stream_twitch = $request->twitchLink;
            $match->stream_yt = $request->youtubeLink;
            $match->stream_fb =  $request->facebookLink;
            $match->stream_other = $request->otherLink;
            $match->save();
            
            //update sub matches stream links
            \App\Match::where('main_match', $match->id)->update([
                'stream_twitch' =>  $request->twitchLink,
                'stream_yt' =>  $request->youtubeLink,
                'stream_fb' =>  $request->facebookLink,
                'stream_other' =>  $request->otherLink,
            ]);

            return ['success' => true, 'message' => 'Match Why/More Information link updated.'];
        }else{
            return ['success' => false, 'message' => 'Match not found.'];
        }
    }

    public function updateBothAdminBets(Request $request)
    {
        /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $rules = [
            'match_id' => 'required',
            'team_a_bet' => 'required',
            'team_b_bet' => 'required',
        ];
        
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $match_id = $request->match_id;
            //first we get the match
            $match = \App\Match::find($match_id);
            if(!empty($match) && $match->status == 'open'){
                $current_user_id = \Auth::id();
                //get admin user ids
                $adminMMUsersIds = getAdminMatchManagersUserIds();
                //delete admin bets
                $deleteAdminBets = \App\Bet::where(
                                        [
                                            'match_id' => $match_id,
                                            'type' => 'matches',
                                            'league_id' => $match->league_id
                                        ]
                                    )->whereIn('user_id',$adminMMUsersIds)->get();

                foreach($deleteAdminBets as $dBets){
                    $dBets->delete();
                }
                //then insert new bets 
                $teamABet = \App\Bet::create(
                    [
                        'match_id' => $match->id,
                        'type' => 'matches',
                        'league_id' => $match->league_id,
                        'team_id' => $match->team_a,
                        'user_id' => $current_user_id,
                        'amount' => $request->team_a_bet
                    ]
                );

                $teambBet = \App\Bet::create(
                    [
                        'match_id' => $match->id,
                        'type' => 'matches',
                        'league_id' => $match->league_id,
                        'team_id' => $match->team_b,
                        'user_id' => $current_user_id,
                        'amount' => $request->team_b_bet
                    ]
                );

                //for draw
                if(!empty($match->team_c)){
                    $teamCBet = \App\Bet::create(
                        [
                            'match_id' => $match->id,
                            'type' => 'matches',
                            'league_id' => $match->league_id,
                            'team_id' => $match->team_c,
                            'user_id' => $current_user_id,
                            'amount' => $request->team_c_bet
                        ]
                    );
                }

                $totalAdminBets = $request->team_a_bet + $request->team_b_bet + $request->team_c_bet;

                \Auth()->user()->decrement('credits',$totalAdminBets);

                return [
                    'success' => true,
                    'message' => 'Admin bets successfully updated.',
                    'errors' => [],
                ]; 

            }else{
                return [
                    'success' => false, 
                    'message' => 'Match not found or not open.',
                    'errors' => [],
                ];
            }


        } else{
            return [
                'success' => false, 
                'message' => 'Admin bets update failed.',
                'errors' => $validator->errors()
            ];
        }
    }
            

    public function updateTournamentAdminBets(Request $request)
    {
        /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $rules = [
            'league_id' => 'required',
            'percentages' => 'required',
            'base_amount' => 'required',
        ];
        
        $validator = \Validator::make($request->all(), $rules);


        if ($validator->passes()) {

            $user = \Auth::user();
            $user_id = $user->id;
            $league_id = $request->league_id;
            $teamPercentages = $request->percentages;
            $baseAmount = $request->base_amount;
            
            if($user->credits < $baseAmount){
                return [
                    'success' => false, 
                    'message' => "You don't have enough credits to place all admin bets.",
                    'errors' => [],
                ];
            }

            //first we get the match
            $league = \App\League::find($league_id);
            if(!empty($league)){
                //get admin user ids
                $adminMMUsersIds = getAdminMatchManagersUserIds();
                //delete admin bets
                $deleteAdminBets = \App\Bet::where(
                                        [
                                            'league_id' => $league_id,
                                            'type' => 'tournament'
                                        ]
                                    )->whereIn('user_id',$adminMMUsersIds)->get();

                foreach($deleteAdminBets as $dBets){
                    $dBets->delete();
                }
                //then insert new bets 
                $createdBets = [];
                foreach($teamPercentages as $teamPercentage){
                    $percentage = $teamPercentage['percentage'];
                    $team_id = $teamPercentage['team_id'];
                    if($percentage > 0){
                        $amount = $baseAmount * ( $percentage / 100);
                        $bet = \App\Bet::create(
                            [
                                'league_id' => $league_id,
                                'type' => 'tournament',
                                'team_id' => $team_id,
                                'user_id' => $user_id,
                                'amount' => $amount
                            ]
                        );

                        \Auth()->user()->decrement('credits',$amount);

                        $createdBets[] = $bet;
                    }

                }




                return [
                    'success' => true,
                    'message' => 'Admin bets successfully updated.',
                    'errors' => [],
                    'bets' => $createdBets
                ]; 

            }else{
                return [
                    'success' => false, 
                    'message' => 'League not found.',
                    'errors' => [],
                ];
            }


        } else{
            return [
                'success' => false, 
                'message' => 'Admin bets update failed.',
                'errors' => $validator->errors()
            ];
        }
            

    }        

    public function removeDuplicateBet(Request $request)
    {
         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $bet_id = $request->bet_id;
        $bet = \App\Bet::find($bet_id);
        if(!empty($bet)){
            $match = $bet->match;
            $currentUser = $bet->user;
            $boyNextDoor = \App\User::find(1066);

            if($match->status == 'open'){

                $bet->delete();

                return [
                    'success' => true, 
                    'message' => 'Bet removed  and credits returned to User: ' . $currentUser->name,
                    'errors' => []
                ];  

            }else if($match->status == 'ongoing'){

                $bet->user_id = 1066;
                $bet->save();

                $boyNextDoor->decrement('credits',$bet->amount);
                $boyNextDoor->touch();
                $currentUser->increment('credits', $bet->amount);
                $currentUser->touch();

                return [
                    'success' => true, 
                    'message' => 'Bet replaced with BND user and credits returned to User: ' . $currentUser->name,
                    'errors' => []
                ];  
                
            }else{
                return [
                    'success' => false, 
                    'message' => 'Cannot remove this bet, match already ended/settled.',
                    'errors' => []
                ];                   
            }

            return [
                'success' => false, 
                'message' => 'Bet does not exists.',
                'errors' => []
            ];                
        }else{
            return [
                'success' => false, 
                'message' => 'Bet does not exists.',
                'errors' => []
            ];            
        }
    }

    public function removeBndBet(Request $request)
    {
         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $bet_id = $request->bet_id;
        $bet = \App\Bet::find($bet_id);

        $bnd_main_user_id = env('BND_MAIN_USER_ID',1066);
        if(!empty($bet)){

            if($bet->user_id != $bnd_main_user_id){
                return [
                    'success' => false, 
                    'message' => 'Cannot remove this bet, not BND bet!',
                    'errors' => []
                ];  
            }

            $match = $bet->match;
            $currentUser = $bet->user;

            if($match->status == 'open'){

                $bet->delete();

                return [
                    'success' => true, 
                    'message' => 'Bet removed and credits returned to User: ' . $currentUser->name,
                    'errors' => []
                ];  

            }else{
                return [
                    'success' => false, 
                    'message' => 'Cannot remove this bet, match already strated/settled.',
                    'errors' => []
                ];                   
            }

            return [
                'success' => false, 
                'message' => 'Bet does not exists.',
                'errors' => []
            ];                
        }else{
            return [
                'success' => false, 
                'message' => 'Bet does not exists.',
                'errors' => []
            ];            
        }
    }    

    public function updateMatchesBndThresholds(Request $request)
    {

        /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $matches = $request->matches;
        $failedMatches = [];



        if(!empty($matches)){

            foreach($matches as $key => $match){
                $match_id = $match['id'];
                $team_a_threshold = $match['team_a_threshold_percent'];
                $team_a_max_threshold = $match['team_a_max_threshold_percent'];
                $team_b_threshold = $match['team_b_threshold_percent'];
                $team_b_max_threshold = $match['team_b_max_threshold_percent'];

                $updateMatch = \App\Match::find($match_id);

                if(!empty($updateMatch)){
                    $updateMatch->team_a_threshold_percent = $team_a_threshold;
                    $updateMatch->team_a_max_threshold_percent = $team_a_max_threshold;
                    $updateMatch->team_b_threshold_percent = $team_b_threshold;
                    $updateMatch->team_b_max_threshold_percent = $team_b_max_threshold;
                    $updateMatch->save();
                }else{
                    $failedMatches[] = $match;
                }
            }

            return [
                'success' => true,
                'message' => 'BND auto-bet threshold settings successfully updated.',
                'failedMatches' => $failedMatches,
            ];

        }else{
            return [
                'success' => false,
                'message' => 'Matches cannot be empty.'
            ];
        }

        dd($matches);
    }
    
    public function listGameTypes() {
        return Datatables::of(\App\GameType::all())->make(true);
    }
    
    public function addEditGameTypes(Request $request) {
        $rules = [
            'name' => 'required|alpha_num|unique:game_types,name,'.$request->type_id,
            'description' => 'required'
        ];
        
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $type = \App\GameType::find($request->type_id);
            if($type) {
                $type->fill($request->all());
                $type->save();
            } else
                $type = \App\GameType::create($request->all());
            return ['success' => true, 'type' => $type];
        } else
            return ['success' => false, 'errors' => $validator->errors()];
    }
    
    public function delGameTypes(Request $request) {
        $type = \App\GameType::find($request->type_id);
        if(\App\Team::where('type', $type->name)->count()) {
            return ['error' => 'Could not delete type. Currently being used by teams'];
        } else
            $type->delete();
        return ['success' => true];
    }
    
    public function getAllTeamsRaw($type = '') {
        return empty($type) ? \App\Team::all() :  \App\Team::where('type',$type)->get();
    }

    public function setFavoriteTeam(Request $request) {
        $league = League::find($request->league_id);
        $league->teams()->updateExistingPivot($request->team_id, [
            'is_favorite' => $request->favopt
        ]);
    }
    
    public function getOngoingLeagues() {
        return League::ongoing()->orderBy('created_at', 'desc')->with('teams')->get();
    }

    public function setLeagueStatus(Request $request) {
        $league = League::find($request->league_id);
        if ($league) {
            switch ($request->type) {
                case 'inactive':
                    $league->status = 0;
                    break;
                case 'undo_inactive':
                    $league->status = 1;
                    break;
                case 'expire':
                    if($league->matches()->activeMatches()->count())
                        return ['error' => 'Could not process request. There are still active matches for this League.'];
                    else
                        $league->expired = 1;
                    break;
                case 'undo_expire':
                    $league->expired = 0;
                    break;
            }
            $league->save();
            return ['success' => true];
        } else {
            return ['error' => 'Could not process request. Please try again'];
        }
    }

    public function addEditTeams(Request $request) {
        $rules = [
            'name' => 'required',
            'shortname' => 'required|max:20',
            'type' => 'required',
            'image' => $request->team_id ? 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $team = $request->team_id ? \App\Team::find($request->team_id) : new \App\Team;
            $team->name = $request->name;
            $team->shortname = $request->shortname;
            $team->type = $request->type;
            if ($request->image) {
                $image_file = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(storage_path('app/public'), $image_file);
                $team->image = 'storage/' . $image_file;
            }
            $team->save();
            return ['success' => true, 'team' => $team];
        } else
            return ['succss' => false, 'errors' => $validator->errors()];
    }

    public function deleteTeam(Request $request) {
        $team = \App\Team::find($request->team_id);
        if ($team->matches()->count() == 0) {
            $team->delete();
            return ['success' => true];
        } else
            return ['success' => false, 'error' => 'This team already exists on some matches and cannot be deleted.'];
    }
    
    public function addEditLeagues(Request $request) {

        $validator = \Validator::make($request->all(), [
                    'name' => 'required',
                    'description' => 'required',
                    'type' => 'required',
                    'teams' => 'nullable|array',
                    'image' => $request->league_id ? 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'bottom_image' => $request->league_id ? 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes()) {

            $input = $request->all();
            if ($request->image) {
                $input['image'] = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(storage_path('app/public'), $input['image']);
            }
            if ($request->bottom_image) {
                $input['bottom_image'] = time() . '.' . $request->bottom_image->getClientOriginalExtension();
                $request->bottom_image->move(storage_path('app/public'), $input['bottom_image']);
            }

            if ($request->league_id) {
                $league = \App\League::find($request->league_id);
                if ($league->betting_status != -1) {
                    $league->type = $request->type;
                    $league->name = $request->name;
                    $league->description = $request->description;
                    $league->status = $request->status;
                    $league->favorites_minimum = $request->favorites_minimum;
                    $league->betting_status = $request->betting_status;
                    if($request->expired && $league->matches()->activeMatches()->count())
                        return ['error' => ['There are active matches for this league and cannot set to expire!']];
                    else
                        $league->expired = $request->expired;
                    $league->betting_fee = $request->betting_fee / 100;
                    if ($request->image)
                        $league->image = $input['image'];
                    if ($request->bottom_image)
                        $league->bottom_image = 'storage/' . $input['bottom_image'];
                    $league->save();
                    $league->teams()->sync($request->teams);
                    
                    // Remove admin bets if betting is closed
                    if ($league->betting_status == 0) {
                        $adminMMUsersIds = getAdminMatchManagersUserIds();
                        $whereBetDelete = [
                            'league_id' => $league->id,
                            'type' => 'tournament',
                        ];

                        $adminBetsDelete = \App\Bet::where($whereBetDelete)->whereIn('user_id',$adminMMUsersIds)->get();
                        foreach($adminBetsDelete as $adminBet){
                            $adminBet->delete();
                        }
                        // foreach ($league->bets as $bet) {
                        //     if (hasMatchManagementAccess($bet->user))
                        //         \App\Bet::find($bet->id)->delete();
                        // }
                    }
                }
            } else {

          
                $league = \App\League::create([
                            'type' => $request->type,
                            'name' => $request->name,
                            'description' => $request->description,
                            'image' => $input['image'],
                            'league_winner' => 0
                ]);
                if ($request->bottom_image)
                    $league->bottom_image = 'storage/' . $input['bottom_image'];
                $league->status = $request->status;
                $league->betting_status = $request->betting_status;
                $league->favorites_minimum = $request->favorites_minimum;
                $league->save();
                $league->teams()->sync($request->teams);
            }

            return ['success' => 'done'];
        }

        return response()->json(['error' => $validator->errors()->all()]);
    }

    public function deleteLeagues(Request $request) {
        $league = \App\League::find($request->league_id);
        if ($league->bets->count()) {
            return ['error' => 'There are matches/bets linked to this league and cannot be deleted.'];
        } else
            return ['league' => $league->delete()];
    }

    public function listLeagues(Request $request) {
        $dt = Datatables::of(\App\League::all()->load('teams', 'champion'));
        $dt->addColumn('btn_options', function($dt) {
            $buttons = '';
            if ($dt->betting_status == -1) {
                if ($dt->status) {
                    if (!$dt->expired) {
                        return '<button type="button" class="btn btn-default btn-xs setLeagueStatus" data-sts_type="inactive">Inactive</button> ' .
                                '<button type="button" class="btn btn-warning btn-xs setLeagueStatus" data-sts_type="expire">Expire</button>';
                    } else {
                        if ($dt->updated_at->diffInMinutes(Carbon::now()) < 60)
                            return '<button type="button" class="btn btn-default btn-xs setLeagueStatus" data-sts_type="inactive">Inactive</button> ' .
                                    '<button type="button" class="btn btn-info btn-xs setLeagueStatus" data-sts_type="undo_expire">Undo Expire</button>';
                        else
                            return '<button type="button" class="btn btn-default btn-xs setLeagueStatus" data-sts_type="inactive">Inactive</button>';
                    }
                } else {
                    if (!$dt->expired) {
                        if ($dt->updated_at->diffInMinutes(Carbon::now()) < 60)
                            return '<button type="button" class="btn btn-success btn-xs setLeagueStatus" data-sts_type="undo_inactive">Set Active</button> ' .
                                '<button type="button" class="btn btn-warning btn-xs setLeagueStatus" data-sts_type="expire">Expire</button>';
                        else
                            return '<button type="button" class="btn btn-warning btn-xs setLeagueStatus" data-sts_type="expire">Expire</button>';
                    } else {
                        if ($dt->updated_at->diffInMinutes(Carbon::now()) < 60)
                            return '<button type="button" class="btn btn-success btn-xs setLeagueStatus" data-sts_type="undo_inactive">Set Active</button> ' .
                                '<button type="button" class="btn btn-info btn-xs setLeagueStatus" data-sts_type="undo_expire">Undo Expire</button>';
                        else
                            return '';
                    }
                }
            } else {
                return '';
            }
        });
        return $dt->rawColumns(['btn_options'])->make(true);
    }

    public function listMatches(Request $request) {
        return Datatables::of(\App\Match::mainMatches()->schedNameSort()->with('league', 'teamA', 'teamB', 'teamwinner'))->make(true);
    }
    
    public function showSubMatches(\App\Match $mainmatch) {
        if($mainmatch) {
            if($mainmatch->type == 'main') {
                $sub_matches = $mainmatch->subMatches;
                $sub_matches = $sub_matches->prepend($mainmatch);
                return $sub_matches;
            } else
                return [];
        } else
            return [];
    }

    public function addEditMatches(Request $request) {
        
        $validator = \Validator::make($request->all(), [
                    'league_id' => 'required',
                    'schedule' => 'required|date',
                    'best_of' => 'required',
                    'team_a' => 'sometimes|required',
                    'team_b' => 'sometimes|required',
                    'team_a_initial_odd' => 'sometimes|required',
                    'team_b_initial_odd' => 'sometimes|required',
                    'submatches.*.team_a_initial_odd' => 'sometimes|required',
                    'submatches.*.team_b_initial_odd' => 'sometimes|required',
        ]);

        if ($validator->passes()) {
            $input = $request->all();
            $input['schedule'] = date('Y-m-d H:i:s', strtotime($request->schedule));
            $input['fee'] = ($request->fee / 100);
            $input['team_c'] = $request->draw_betting_enabled == 1 ? env('TEAM_DRAW_ID') : NULL;

            if ($request->match_id) {
                $match = \App\Match::find($request->match_id);
                if ($match->status == 'open') {
                    $match->fill($input);
                    $match->save();
                    
                    // Update teams on sub-matches
                    foreach($match->subMatches as $subMatch) {
                        $subMatch->name = str_replace([$subMatch->teamA->name, $subMatch->teamB->name], [$match->teamA->name, $match->teamB->name], $subMatch->name);
                        if($subMatch->is_over_under == 0){
                            $subMatch->team_a = $match->team_a;
                            $subMatch->team_b = $match->team_b;
                        }
                        $subMatch->save();
                    }
                    
                    if (in_array($request->status, ['cancelled', 'forfeit'])) {
                        foreach ($match->bets as $bet) {
                            $user = $bet->user;
                            // $user->credits += $bet->amount;
                            // $user->save();
                            $user->increment('credits', $bet->amount);
                        }
                        
                        // Cancel all bets from existing sub-matches
                        foreach($match->subMatches as $subMatch) {
                            foreach ($subMatch->bets as $bet) {
                                $user = $bet->user;
                                // $user->credits += $bet->amount;
                                // $user->save();
                                $user->increment('credits', $bet->amount);
                            }
                            $subMatch->status = $request->status;
                            $subMatch->save();
                        }
                        $match->status = $request->status;
                        $match->save();
                    }

                    if ($match->status == 'ongoing') {

                        $match->date_set_live = Carbon::now();
                        $match->save();

                        setupOngoingMatch($match);
                        $job = (new SetMatchBetsRatio( ['match' => $match ] ))->delay(Carbon::now()->addMinutes(3));
                        $this->dispatch($job);
                    }

                    return ['success' => 'done1'];
                } else {
                    return ['error' => [
                            'status' => ['Cannot edit match. This Match has already started!']
                    ]];
                }
            } else {

                $mainmatch = \App\Match::create($input);
                if($request->submatches) {
                    foreach($request->submatches as $sub_match) {
                        unset($input['team_c']);
                        $submatch = \App\SubMatch::create($input);
                        $submatch->type = 'sub';
                        $submatch->game_grp = $sub_match['ctr'];
                        $submatch->sub_type = $sub_match['subtype'];
                        $submatch->name = $sub_match['name'];
                        $submatch->label = $sub_match['name'];
                        $submatch->team_a_initial_odd = !empty($sub_match['team_a_initial_odd']) ? $sub_match['team_a_initial_odd'] : 0;
                        $submatch->team_b_initial_odd = !empty($sub_match['team_b_initial_odd']) ? $sub_match['team_b_initial_odd'] : 0;
                        if($sub_match['is_over_under'] == 1){
                            $submatch->team_a = !empty( env('TEAM_OVER_ID') ) ? env('TEAM_OVER_ID') : $submatch->team_a;
                            $submatch->team_b = !empty( env('TEAM_UNDER_ID') ) ? env('TEAM_UNDER_ID') : $submatch->team_b;
                        }
                        if($sub_match['ctr'] > 1)
                            $submatch->schedule = Carbon::createFromTimeString($request->schedule)
                                ->addHours($sub_match['ctr']-1);
                        $submatch->main_match = $mainmatch->id;
                        $submatch->save();
                    }
                }
                return ['success' => 'done'];
            }
        } else
            return ['error' => $validator->errors(), 'data' => $request->all()];
    }
    //edit match standings (match.blade.php)
    public function editMatch(Request $request) {
        $validator = \Validator::make($request->all(), [
                    'match_id' => 'required',
                    'schedule' => 'sometimes|nullable|date'
        ]);

        // dd($request->all());
        if ($validator->passes()) {
            $match = \App\Match::find($request->match_id);
            
            if ($match->status == 'open') {
                $match->name = $request->name;
                $match->label = $request->label;
                $match->fee = ($request->fee / 100);

                $match->is_initial_odds_enabled = $request->is_initial_odds_enabled;

                if(!empty($request->initial_odds_change_allowed)){
                    $match->team_a_initial_odd = $request->is_initial_odds_enabled == 1 ? $request->team_a_initial_odd: 0;
                    $match->team_b_initial_odd = $request->is_initial_odds_enabled == 1 ? $request->team_b_initial_odd: 0;
                }

                
                if($request->status){
                    $match->status = $request->status;
                }
                    
                if($request->schedule){
                    $match->schedule = date('Y-m-d H:i:s', strtotime($request->schedule));
                }
                    
                $toBeUpdatedFields = $match->getDirty();
                $originalValues = $match->getOriginal();


                if(!empty($toBeUpdatedFields['team_a_initial_odd']) || !empty($toBeUpdatedFields['team_b_initial_odd']) ){
                    $userName = Auth()->user()->name;
                    $userId = Auth()->user()->id;

                    $message = "Initial odds of Match: #{$match->id} updated by user: {$userName} with user id of {$userId} 
                        from [{$originalValues['team_a_initial_odd']}-{$originalValues['team_b_initial_odd']}] to [{$request->team_a_initial_odd}-{$request->team_b_initial_odd}]"; 

                    $oddLogsRepo = new UpdatedMatchOddsLogsRepository;
                    $oddLogsRepo->create([
                        'match_id' => $match->id,
                        'message' => $message
                    ]);
                }

            

                if ($match->status == 'ongoing'){

                    $match->date_set_live = Carbon::now();

                    setupOngoingMatch($match);

                    $job = (new SetMatchBetsRatio( ['match' => $match ] ))->delay(Carbon::now()->addMinutes(3));
                    $this->dispatch($job);

                }

                 $match->save();

                return ['success' => 'done1'];
            }
            elseif($match->status =='ongoing'){
                $match->teama_score = $request->teama_score;
                $match->teamb_score = $request->teamb_score;
                $match->save();
                return ['success' => 'done1'];
            } 
            elseif($match->status =='settled'){
                $match->teama_score = $request->teama_score;
                $match->teamb_score = $request->teamb_score;
                $match->save();
                return ['success' => 'done1'];
            }
            elseif($match->status =='draw'){
                $match->teama_score = $request->teama_score;
                $match->teamb_score = $request->teamb_score;
                $match->save();
                return ['success' => 'done1'];
            }
        } else
            return ['error' => $validator->errors(), 'data' => $request->all()];
    }
    //set match score (matchmanager)
    public function editMatchStanding(Request $request) {
        $validator = \Validator::make($request->all(), [
        ]);

        if ($validator->passes()) {
            $input = $request->all();
            $input['teama_score'] = ($request->teama_score);
            $input['teamb_score'] = ($request->teamb_score);
            if ($request->match_id) {
                $match = \App\Match::find($request->match_id);
                if ($match->status == 'ongoing') {
                    $match->fill($input);
                    $match->save();

                    return ['success' => 'done1'];
                }
                if ($match->status == 'settled') {
                    $match->fill($input);
                    $match->save();

                    return ['success' => 'done1'];
                }
            }
        } else
            return ['error' => $validator->errors(), 'data' => $request->all()];
    }

    public function setMatchOpen(Request $request) {
        $match = \App\Match::find($request->match_id);
        if ($match && $match->status == 'ongoing') {
            if (Carbon::now()->diffInMinutes($match->updated_at) <= 10) {
                return $this->openOngoingMatch($match);
            } else {
                if($request->passcode && $request->passcode == env('OVERRIDE_CODE'))
                    return $this->openOngoingMatch($match);
                else
                    return ['success' => false, 'need_auth' => true, 'error' => 'Cannot open match, please contact admin to override!'];
            }
        } else
            return ['success' => false];
    }
    
    private function openOngoingMatch(\App\Match $match) {
        $match->status = 'open';
        $match->re_opened = true;
        $match->date_reopened = Carbon::now();
        $match->save();

        if ($match->type == 'main') {
            foreach ($match->subMatches as $subMatch) {
                if ($subMatch->status == 'ongoing') {
                    $subMatch->status = 'open';
                    $subMatch->re_opened = true;
                    $subMatch->date_reopened = Carbon::now();
                    $subMatch->save();
                }
            }
        } else {
            $subMatches = \App\SubMatch::where('main_match', $match->main_match)
                            ->where('game_grp', $match->game_grp)
                            ->where('id', '!=', $match->id)->get();
            foreach ($subMatches as $submatch) {
                if ($submatch->status == 'ongoing') {
                    $submatch->status = 'open';
                    $submatch->re_opened = true;
                    $submatch->date_reopened = Carbon::now();
                    $submatch->save();
                }
            }
        }
        return ['success' => true];
    }

    public function extendMatchTime(Request $request) {
        $match = \App\Match::find($request->match_id);
        if ($match) {
            $match->schedule = $match->schedule->addMinutes($request->ext_time);
            $match->save();
            return ['success' => true];
        } else
            return ['success' => false, 'data' => $request->all()];
    }

    private function updateBetRatio($betID, $ratio) {
        $bet = \App\Bet::find($betID);
        $bet->ratio = $ratio;
        $bet->save();
    }

    public function deleteMatches(Request $request) {
        $match = \App\Match::find($request->match_id);
        if ($match->bets->count()) {
            return ['error' => 'There are active bets on this match and cannot be deleted.'];
        } else {
            if ($match->subMatches) {
                foreach ($match->subMatches as $subMatch) {
                    if ($subMatch->bets->count()) {
                        return ['error' => 'There are active bets on this match and cannot be deleted.'];
                    }
                }
                return ['match' => $match->delete()];
            } else
                return ['match' => $match->delete()];
        }
    }

    public function settleMatches(Request $request) {
        
         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $validator = \Validator::make($request->all(), [
                    'match_id' => 'required',
                    'team_winner' => 'required'
        ]);
        
        if ($validator->passes()) {
            $match = \App\Match::find($request->match_id);
            $currentMatchStatus = $match->status;

            if ($match->status != 'settled') {
                
                if($request->team_winner == 'cancelled' && $match->subMatches()->liveMatches()->count() ){

                    return ['error' => 'There are still active sub matches, Please settle them first!'];

                }else if (  $request->team_winner  == 'draw' && $match->type == 'main' && $match->subMatches()->activeMatches()->count() ){

                    return ['error' => 'There are still active sub matches, Please settle them first!'];

                }else {
                   
                    $total_circulating_credits = getCirculatingCredits();
                 
                    $match->team_winner = in_array($request->team_winner, ['draw', 'cancelled']) ? null : $request->team_winner;
                    $match->status = in_array($request->team_winner, ['draw', 'cancelled']) ? $request->team_winner : 'settled';
                    $match->date_settled = Carbon::now();
                    $match->save();

                   
                    if (in_array($request->team_winner, ['draw', 'cancelled'])  ) {

                        if(in_array($currentMatchStatus, ['settled', 'draw','cancelled'])){
                             return ['error' => 'Match was already settled, please refresh page!'];
                        }

                        if( ( ($currentMatchStatus == 'open' || $currentMatchStatus == 'ongoing') && $request->team_winner == 'cancelled') || ($currentMatchStatus == 'ongoing' && $request->team_winner == 'draw' )){

                            $drawsBets = DB::table('bets')
                            ->join('users','users.id','=','bets.user_id')
                            ->where(
                                [
                                    'match_id' => $match->id,
                                ])
                            ->update([
                                'gains' => 0,
                                'credits' => DB::raw(' credits + amount ')
                            ]);

                        }
                        



                    }else{

                        $winnersBets = DB::table('bets')
                                        ->join('users','users.id','=','bets.user_id')
                                        ->where(
                                            [
                                                'match_id' => $match->id,
                                                'team_id' => $request->team_winner
                                            ])
                                        ->update([
                                            'gains' => DB::raw(' (amount * ratio) - amount '),
                                            'credits' => DB::raw(' credits + (amount * ratio) ')
                                        ]);                                    

                        $losersBets = DB::table('bets')
                                        ->where(
                                            [
                                                ['match_id','=',$match->id],
                                                ['team_id', '!=', $request->team_winner ]
                                            ])
                                        ->update([
                                            'gains' => DB::raw('-1 * amount'),
                                        ]);                 
                    }

                    if ($request->team_winner == 'cancelled'){
                        $this->cancelChildMatches($match);
                    }
                        

                    $this->saveMatchReport($match, $total_circulating_credits);
                    
                   // Log::info('Settle match excution time: ' . ($time2 - $time1). ' | Match ID: ' . $match->id . ' | ' .$match->status);
                    return ['success' => 'done'];
                }
            } else{
                return ['error' => 'Match was already settled, please refresh page!'];
            }
            
        } else{
            return ['error' => $validator->errors()];
        }
            
    }

    public function settleTournament(Request $request) {

         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $validator = \Validator::make($request->all(), [
                    'league_id' => 'required',
                    'team_id' => 'required'
        ]);

        if ($validator->passes()) {
            $league = \App\League::find($request->league_id);
            if ($league->betting_status == 1) {
                return ['success' => false, 'error' => 'Tournament betting is still open! Please close it first.'];
            } else {
                $total_circulating_credits_before = getCirculatingCredits();

                $league->league_winner = $request->team_id;


                $teamRatio = tournamentRatioPerTeam($league->id, $request->team_id);

                $winnersBets = DB::table('bets')
                                ->join('users','users.id','=','bets.user_id')
                                ->where(
                                    [
                                        'bets.type' => 'tournament',
                                        'bets.league_id' => $league->id,
                                        'bets.team_id' => $request->team_id,
                                    ])
                                ->update([
                                    'bets.gains' => DB::raw(" (bets.amount * {$teamRatio}) - amount "),
                                    'users.credits' => DB::raw(" users.credits + ( bets.amount * {$teamRatio} ) "),
                                ]);                                    

                $losersBets = DB::table('bets')
                                ->where(
                                    [
                                        ['type','=', 'tournament'],
                                        ['league_id', '=', $league->id],
                                        ['team_id', '!=', $request->team_id ]
                                    ])
                                ->update([
                                    'gains' => DB::raw('-1 * amount'),
                                ]);      

                 

                // $bets = \App\Bet::tournament($request->league_id)->get();

                // foreach ($bets as $bet) {
                //     $user = $bet->user;
                //     $teamRatio = tournamentRatioPerTeam($league->id, $bet->team_id);

                //     if ($bet->team_id == $league->league_winner) {
                //         $gains = ($bet->amount * $teamRatio);
                //         $bet->gains = ($gains - $bet->amount);
                //         $user->credits += $gains;
                //         $user->save();
                //     } else
                //         $bet->gains = -($bet->amount);

                //     $bet->ratio = $teamRatio;
                //     $bet->save();
                // }

                $league->circulating_credits_before_settled = $total_circulating_credits_before;
                $league->betting_status = -1;
                
                $total_circulating_credits_after = getCirculatingCredits();    
                $league->circulating_credits_after_settled = $total_circulating_credits_after;
                $league->save();

                // Fee
                $fee = new Fee;
                $fee->meta_key = 'tournament';
                $fee->meta_value = $league->id;
                $fee->percent = $league->betting_fee;
                $fee->collected = \App\Bet::tournament($league->id)->sum('amount') * $league->betting_fee;
                $fee->save();

                $job = (new SendOutrightReport( ['league' => $league ] ))->delay(Carbon::now()->addMinutes(5));
                $this->dispatch($job);

                //new commissions method
                $this->dispatch(new GenerateTournamentBetsAffliatesCommissions($league));
                
                return ['success' => true];
            }
        } else
            return ['error' => $validator->errors()];
    }
    
    private function cancelChildMatches(\App\Match $match) {

         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $subMatches = $match->type == 'main' ? 
                            $match->subMatches->where('game_grp', '<=', 1)->where('status','open') : 
                            (
                                $match->sub_type == 'main' ?  
                                    \App\SubMatch::where('main_match', $match->main_match)
                                        ->where('id','!=', $match->id)
                                        ->where('game_grp', $match->game_grp)
                                        ->where('status','open')->get() : 
                                    NULL
                            );

        if(!empty($subMatches)){
            foreach($subMatches as $key => $subMatch){

                $subMatch->status = 'cancelled';
                $subMatch->date_settled = Carbon::now();
                $subMatch->save();

                $cancelledBets = DB::table('bets')
                                    ->join('users','users.id','=','bets.user_id')
                                    ->where(
                                        [
                                            'match_id' => $subMatch->id,
                                        ])
                                    ->update([
                                        'gains' => 0,
                                        'credits' => DB::raw(' credits + amount ')
                                    ]);       
            }
        }


    }
    
    /**
     * Save match report
     * 
     * @param App\Match $match
     * @return Response $response
     */
    public function saveMatchReport(\App\Match $match,$total_circulating_credits) 
    {

        $this->dispatch(new SendMatchReport(['type' => 'settled', 'match_id' => $match->id,'total_circulating_credits' => $total_circulating_credits,'settled_by' => \Auth::user()->id]));
        
        //new commissions method
        $this->dispatch(new GenerateMatchBetsAffliatesCommissions($match));

        return response()->json(['success' => true, 'message' => 'Match report successfully save']);
    }
    /**
     * Get match reports
     * @param int match_id
     * @return html table of reports
     */
    public function returnMatchReport(Request $request)
    {
        
        if (hasMatchManagementAccess(\Auth::user())) {
            $currentTime = Carbon::now();
            $match = \App\Match::find($request->match_id);
            $total_team_a_bettors=0;
            $total_team_a_bets=0;
            $total_team_a_profit=0;
            $total_team_b_bettors=0;
            $total_team_b_bets=0;
            $total_team_b_profit=0;

            $total_team_a_bot_bets = 0;
            $total_team_b_bot_bets = 0;

            $a_users_list = [];
            $b_users_list = [];
            $div = '';
            $div .= '<div class="m-container1" style="width: 98% !important;">';
            $div .= '<div class="main-ct" style="margin-bottom: 0">';
            $div .= '<div class="title">Admin Report <span id="report_last_updated" style="font-size: 14px; float:right; margin-right: 10px; "><i>Last Updated:'.$currentTime.'</i></span></div>';
            $div .= '<div class="clearfix"></div>';
            $div .= '<div class="matchmain">';
            $div .= '<div class="col-md-12" style="max-height: 500px; overflow: auto">';
            $div .= '<div class="col-md-6">';
            $div .= '<table id="team_a_table" class="table table-striped" width="100%">';
            $div .= '<thead>';
            $div .= '<tr>';
            $div .= '<th>ID</th>';
            $div .= '<th>BET ID</th>';
            $div .= '<th>Bet vs ID</th>';
            $div .= '<th>'.$match->teamA->name.' Bettors</th>';
            $div .= '<th>Bet Amount</th>';
            $div .= '<th>Profit/Loss</th>';
            $div .= '</tr>';
            $div .= '</thead>';
            $div .= '<tbody>';
            $color='black';

            $team_a_bets = $match->bets->load('user')->where('team_id', $match->team_a)->sortBy('user_id');
            foreach ( $team_a_bets as $bet) {
                if($match->team_a == $bet->team_id){
                    if( $bet->user->id == env('SERENA_NEXT_DOOR_ID') || $bet->user->id == env('TOMBOY_NEXT_DOOR_ID') ){
                        $style = 'style="background:#a6ebff;"';
                        $total_team_a_bot_bets += $bet->amount;
                    }else if($bet->user->type == 'user'){
                        if(in_array($bet->user->id,$a_users_list)){
                            $style = 'style="background:#ffbaca;"';
                        }else{
                            $style= '';
                        }
                    }else{
                        $style = 'style="background:#bae6ff;"';
                    }
                    $total_team_a_bettors ++;

                    $profit = $bet->ratio ? ($bet->amount * $bet->ratio) - $bet->amount : 0;
                    // $profit = in_array($match->status, ['open', 'ongoing']) ? 0 : $bet->team->potentialMatchWinningPerUser($bet->match->id, $bet->user->id) - $bet->amount;
                    $div .= '<tr '.$style.'>';
                        //$div .='<td><a href="#" class="audit-user" data-user-id="'.$bet->user->id.'" data-user-name="'.$bet->user->name.'">'.$bet->user->id.'</a></td>';
                        $div .='<td>'.$bet->user->id.'</td>';
                        $div .='<td>'.$bet->id.'</td>';
                        $div .='<td>'.$bet->bot_bet_against_bet_id.'</td>';
                        $div .='<td>'.$bet->user->name.'</td>';
                        $div .= '<td>&#8369; '.number_format($bet->amount,2).'</td>';
                        if($bet->team_id == $match->team_winner && $match->status =='settled'){
                            $color = 'green';
                            $div .= '<td><span style="color:green">&#8369; '.number_format($profit,2).'</span></td>';
                            $total_team_a_profit+=$profit;
                        }elseif ($match->status =='draw'){
                            $color='black';
                            $div .= '<td><span style="color:black">&#8369; 0.00</span></td>';
                            
                        }elseif ($bet->team_id != $match->team_winner && $match->status =='settled') {
                            $color='red';
                            $div .= '<td><span style="color:red">&#8369; '.number_format($bet->amount,2).'</span></td>';
                            $total_team_a_profit+=$bet->amount;
                        }elseif ($match->status =='open' || $match->status =='ongoing') {
                            $color='black';
                            if($bet->user->type == 'user')
                                $div .= '<td></td>';
                            else
                                $div .= '<td><button type="button" class="btn btn-danger btn-xs cancelAdminBet" data-betid="'.$bet->id.'">Cancel</button></td>';
                        }
                    $div .= '</tr>';
                    $total_team_a_bets+=$bet->amount;
                    array_push($a_users_list,$bet->user->id);
                }
            }
            $div .= ' <tr>';
            $div .= '<td></td>';
            $div .= '<td></td>';
            $div .= '<td></td>';
            $div .= '<td></td>';            
            $div .= '<td style="font-weight: bold">&#8369; '.number_format($total_team_a_bets,2).'</td>';
            $div .= '<td style="font-weight: bold"><span style="color:'.$color.'">'.($total_team_a_profit != 0 ? '&#8369; '.number_format($total_team_a_profit,2) : '').'</span></td>';
            $div .= '</tr>';
            $div .= '</tbody>';
            $div .= '</table>';
            $div .= '</div>';
            $div .= '<div class="col-md-6">';
            $div .= '<table id="team_a_table" class="table table-striped" width="100%">';
            $div .= '<thead>';
            $div .= '<tr>';
            $div .= '<th>ID</th>';
            $div .= '<th>BET ID</th>';
            $div .= '<th>Bet vs ID</th>';            
            $div .= '<th>'.$match->teamB->name.' Bettors</th>';
            $div .= '<th>Bet Amount</th>';
            $div .= '<th>Profit/Loss</th>';
            $div .= '</tr>';
            $div .= '</thead>';
            $div .= '<tbody>';

            $team_a_bets = $match->bets->where('team_id', $match->team_b)->sortBy('user_id');

            foreach ($team_a_bets as $bet) {
                if($match->team_b == $bet->team_id){
                    if( $bet->user->id == env('SERENA_NEXT_DOOR_ID') || $bet->user->id == env('TOMBOY_NEXT_DOOR_ID') ){
                        $style = 'style="background:#a6ebff;"';
                        $total_team_b_bot_bets += $bet->amount;
                    }else if($bet->user->type == 'user'){
                        if(in_array($bet->user->id,$a_users_list)){
                            $style = 'style="background:#ffbaca;"';
                        }else{
                            $style= '';
                        }
                    }else{
                        $style = 'style="background:#bae6ff;"';
                    }
                    $total_team_b_bettors ++;

                    $profit = $bet->ratio ? ($bet->amount * $bet->ratio) - $bet->amount : 0;
                    // $profit = in_array($match->status, ['open', 'ongoing']) ? 0 : $bet->team->potentialMatchWinningPerUser($bet->match->id, $bet->user->id) - $bet->amount;
                    $div .= '<tr '.$style.'>';
                        //$div .='<td><a href="#" class="audit-user" data-user-id="'.$bet->user->id.'" data-user-name="'.$bet->user->name.'">'.$bet->user->id.'</a></td>';
                        $div .='<td>'.$bet->user->id.'</td>';
                        $div .='<td>'.$bet->id.'</td>';
                        $div .='<td>'.$bet->bot_bet_against_bet_id.'</td>';                        
                        $div .='<td>'.$bet->user->name.'</td>';
                        $div .= '<td>&#8369; '.number_format($bet->amount,2).'</td>';
                        if($bet->team_id == $match->team_winner && $match->status =='settled'){
                            $color = 'green';
                            $div .= '<td><span style="color:green">&#8369; '.number_format($profit,2).'</span></td>';
                            $total_team_b_profit+=$profit;
                        }elseif ($match->status =='draw'){
                            $color='black';
                            $div .= '<td><span style="color:black">&#8369; 0.00</span></td>';
                            
                        }elseif ($bet->team_id != $match->team_winner && $match->status =='settled') {
                            $color='red';
                            $div .= '<td><span style="color:red">&#8369; '.number_format($bet->amount,2).'</span></td>';
                            $total_team_b_profit+=$bet->amount;
                        }elseif ($match->status =='open' || $match->status =='ongoing') {
                            $color='black';
                            if($bet->user->type == 'user')
                                $div .= '<td></td>';
                            else
                                $div .= '<td><button type="button" class="btn btn-danger btn-xs cancelAdminBet" data-betid="'.$bet->id.'">Cancel</button></td>';
                        }
                    $div .= '</tr>';
                    $total_team_b_bets+=$bet->amount;
                    array_push($b_users_list,$bet->user->id);
                }
            }
            $div .= ' <tr>';
            $div .= '<td></td>';
            $div .= '<td></td>';
            $div .= '<td></td>';
            $div .= '<td></td>';  
            $div .= '<td style="font-weight: bold">&#8369; '.number_format($total_team_b_bets,2).'</td>';
            $div .= '<td style="font-weight: bold"><span style="color:'.$color.'">'.($total_team_b_profit != 0 ? '&#8369; '.number_format($total_team_b_profit,2) : '').'</span></td>';
            $div .= '</tr>';
            $div .= '</tbody>';
            $div .= '</table>';
            $div .= '</div>';
            $div .= '</div>';
            $div .= '<div class="col-md-8" style="margin-top: 10px;">';
            $total_bets = $total_team_a_bets+$total_team_b_bets;
            switch ($match->status) {
                case 'draw':
                    $collected_fees = 0.00;
                    $total_payout = number_format(0,2);
                    break;
                case 'open':
                    $collected_fees = 0.00;
                    $total_payout = number_format(0,2);
                    break;
                case 'settled':
                    $collected_fees = $total_bets*$match->fee;
                    $total_payout = number_format($total_bets-$collected_fees,2);
                    break;
                case 'ongoing':
                    $collected_fees = $total_bets*$match->fee;
                    $total_payout = number_format($total_bets-$collected_fees,2);
                    break;
                case 'cancelled':
                    $collected_fees = 0.00;
                    $total_payout = number_format(0,2);
                    break;
                
                default:
                    $collected_fees = 0.00;
                    $total_payout = number_format(0,2);
                    # code...
                    break;
            }
            
            $div .= '<dt class="col-sm-5">Total Bettors on '.$match->teamA->name.'</dt>';
            $div .= '<dd class="col-sm-7">'.$total_team_a_bettors.'</dd>';
            $div .= '<dt class="col-sm-5">Total Bets on '.$match->teamA->name.'</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.number_format($total_team_a_bets,2).'</dd>';
            $div .= '<dt class="col-sm-5">Average Bets on '.$match->teamA->name.'</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.($total_team_a_bettors != 0 ? number_format($total_team_a_bets/$total_team_a_bettors,2) : '').'</dd>';
            $div .= '<dt class="col-sm-5">Total Bettors on '.$match->teamB->name.'</dt>';
            $div .= '<dd class="col-sm-7">'.$total_team_b_bettors.'</dd>';
            $div .= '<dt class="col-sm-5">Total Bets on '.$match->teamB->name.'</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.number_format($total_team_b_bets,2).'</dd>';
            $div .= '<dt class="col-sm-5">Average Bets on '.$match->teamB->name.'</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.($total_team_b_bettors != 0 ? number_format($total_team_b_bets/$total_team_b_bettors,2) : '').'</dd>';
            $div .= '<dt class="col-sm-5">Total Bettors on this match</dt>';
            $div .= '<dd class="col-sm-7">'.($total_team_b_bettors+$total_team_a_bettors).'</dd>';
            $div .= '<dt class="col-sm-5">Total Bets on this match</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.number_format($total_bets,2).'</dd>';
            $div .= '<dt class="col-sm-5">Average Bets on this match</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.($total_team_b_bettors != 0 || $total_team_a_bettors != 0 ? number_format($total_bets/($total_team_b_bettors+$total_team_a_bettors),2) : '').'</dd>';
            $div .= '<dt class="col-sm-5">Match Fee</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.($match->fee*100).'%</dd>';
            $div .= '<dt class="col-sm-5">Total Fees collected on this match</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.number_format($collected_fees,2).'</dd>';
            $div .= '<dt class="col-sm-5">Total Payout</dt>';
            $div .= '<dd class="col-sm-7">&#8369; '.$total_payout.'</dd>';


            $total_team_a_bot_bets = number_format($total_team_a_bot_bets,2);
            $total_team_b_bot_bets = number_format($total_team_b_bot_bets,2);

            $div .= "<dt class='col-sm-5'>Total BetBots on {$match->teamA->name}</dt>";
            $div .= "<dd class='col-sm-7'>&#8369;{$total_team_a_bot_bets}</dd>";
            $div .= "<dt class='col-sm-5'>Total BetBots on {$match->teamB->name}</dt>";
            $div .= "<dd class='col-sm-7'>&#8369;{$total_team_b_bot_bets}</dd>";         
            
            $div .= '</div>';
            $div .= '</div>';
            $div .= '</div>';
            $div .= '</div>';

           // return $div;

            $team_a_ratio = $total_team_a_bets ? $total_bets / $total_team_a_bets * (1 - $match->fee) : 0;
            $team_b_ratio = $total_team_b_bets ? $total_bets / $total_team_b_bets * (1 - $match->fee) : 0;

            $team_a_percentage = $total_bets ? ($total_team_a_bets / $total_bets) * 100 : 0;
            $team_b_percentage = $total_bets ? ($total_team_b_bets / $total_bets) * 100 : 0;

            $returnJson = [
                'div' => $div,
                'team_a_ratio' => $match->bets ? $team_a_ratio : 2,
                'team_b_ratio' => $match->bets ? $team_b_ratio : 2,
                'team_a_percentage' => $match->bets ? $team_a_percentage : 50,
                'team_b_percentage' => $match->bets ? $team_b_percentage : 50,
                'total_team_a_bets' => $total_team_a_bets,
                'total_team_b_bets' => $total_team_b_bets,
                'total_bets' => $total_bets
            ];

            return json_encode($returnJson);

        } else {
            return '';
        }
    }

    /**
     * Get League reports
     * @param int league_id
     * @return html table of reports
     */
    public function returnLeagueReport(Request $request)
    {
        if (hasMatchManagementAccess(\Auth::user())) {
            return returnLeagueReport($request->league_id,'div');
        } else {
            return '';
        }
    }

    public function returnLeagueReportMail(Request $request)
    {
        $league_id = $request->league_id ? $request->league_id : 0;
        if (hasMatchManagementAccess(\Auth::user()) && !empty($league_id)) {
          
            $league = League::find($league_id);
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

            //$job = (new SendOutrightReport( ['league' => $league ] ))->delay(Carbon::now()->addMinutes(5));
            // $job = (new SendOutrightReport( ['league' => $league ] ));
            // $this->dispatch($job);
            
            return view('emails.outright-report')->with([
                'data' => $data
            ]);
        } else {
            return '';
        }        
    }

    public function updateLeagueTbdTeam(Request $request){
        if (hasMatchManagementAccess(\Auth::user())) {

            $league_id = $request->league_id;
            $tbd = $request->tbd_id;
            $team_id = $request->team_id;
            $league = \App\League::find($league_id);
            $teamsTemp = explode(',', $request->teams);
            $teams = [$team_id];
            
            if(!empty($teamsTemp)){
                foreach($teamsTemp as $team){
                    if($team != $tbd){
                        $teams[] = $team;
                    }
                  
                }
            }
            
            if(!empty($league)){
                $league->teams()->sync($teams);
                \App\Bet::where([
                    'league_id' => $league_id,
                    'type' => 'tournament',
                    'team_id' => $tbd
                ])->update(['team_id' => $team_id]);
            }  
            
            return ['success' => 'done1'];

        }
        return '';
    }

    public function getActiveLeagues()
    {
        $leagues = League::active()->orderBy('display_order','asc')->get();

        return [
            'leagues' => $leagues
        ];
    }

    public function updateLeagueDisplayOrder(Request $request)
    {
        $leagues = $request->leagues;
        if(!empty($leagues)){
            
            foreach($leagues as $order => $league){
                $updateLeague = League::find($league['id']);
                $updateLeague->display_order = $order;
                $updateLeague->save();
            }

            return [
                'success' => true,
                'message' => 'Outright display orders updated.'
            ];

        }else{

            return [
                'success' => false,
                'message' => 'Leagues cannot be empty.'
            ];
        }
    }

    public function revertToOngoing(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                    'match_id' => 'required',
        ]);

        if ($validator->passes()) {
            $match = \App\Match::find($request->match_id);
            if(in_array($match->status, ['ongoing', 'cancelled', 'settled'])){
                                        
                $getBackwinnersBets = DB::table('bets')
                                ->join('users','users.id','=','bets.user_id')
                                ->where(
                                    [
                                        'match_id' => $match->id,
                                        'team_id' => $match->team_winner
                                    ])
                                ->update([
                                    'gains' => NULL, //set back gains to NULL
                                    'credits' => DB::raw(' credits - (amount * ratio) ')
                                ]);   

                $getBacklosersBets = DB::table('bets')
                                ->where(
                                    [
                                        ['match_id','=',$match->id],
                                        ['team_id', '!=', $match->team_winner ]
                                    ])
                                ->update([
                                    'gains' => NULL,
                                ]);  
                

                $match->status = 'ongoing';
                $match->team_winner = NULL;
                $match->save();    

                return [
                    'success' => true,
                    'message' => "Reverted Match #{$request->match_id} to LIVE."
                ];                
            }else{
                return [
                    'success' => false,
                    'message' => "Match #{$request->match_id} not settled yet."
                ];
            }
        }else{
            return [
                'success' => false,
                'message' => 'Match ID required.',
            ];
        }    
    }

    public function lockForMatchBetting(Request $request)
    {
        $match_id = $request->match_id ?? 0;
        $user = \App\User::find(Auth()->id());
        $isAllowed = in_array(Auth()->id(), getSuperAdminIds());

        if($isAllowed){
            return [ 'success' => disableBettingGoingLiveMatch($match_id) ];

        }else{
            return abort(403);
        }
    }

    public function unlockForMatchBetting(Request $request)
    {
        $match_id = $request->match_id ?? 0;
        $user = \App\User::find(Auth()->id());
        $isAllowed = in_array(Auth()->id(), getSuperAdminIds());

        if($isAllowed){
            return [ 'success' => allowBettingGoingLiveMatch($match_id) ];
        }else{
            return abort(403);
        }
    }
    
    
    public function mockSetCommissionsBet()
    {
        $match_id = 116577;
        $match = \App\Match::find($match_id);
  
        $match_id = $match->id; //match id
        $match_settled = $match->date_settled; //settled date
        $match_winner = $match->team_winner; //team winner
        $match_status = $match->status;
        $commission_percentage = env('AFFILIATE_COMMISSIONS_VIA_BETS',0.04);

        if($match_status == 'settled'){ //process only job if Match is settled - meaning match needs to have a winner
            
            $match->load('bets.user'); 
            $bettors = $match->bets->pluck('user');

            $bets = $match->bets; //bets on this match

            //getting list of affliates that we need to loop thru
            $affliateBadgesIds = \App\Badge::where('name','AFFILIATE')->pluck('id');
            $affliates = \DB::table('users')
                        ->join('badge_user','users.id', '=','badge_user.user_id')
                        ->whereIn('badge_user.badge_id',$affliateBadgesIds)
                        ->where('users.voucher_code', '!=', NULL)
                        ->where('users.voucher_percent', '>',0)
                        ->get(); 
            //end getting list of affliates that we need to loop thru
            if(!empty($affliates)){
                
                foreach($affliates as $key => $streamer){

                    $voucherCode = strtolower($streamer->voucher_code);
                    $streamersBettors = $bettors->filter(function ($bettor) use ($voucherCode) {
                            // replace stristr with your choice of matching function
                            return strtolower($bettor->redeem_voucher_code) == $voucherCode;
                        });

                    if($streamersBettors->isNotEmpty()){
                        $streamerBettorBets = $bets->whereIn('user_id', $streamersBettors->pluck('id'))->map(function($bet) use ($commission_percentage){
                            $temp =  collect($bet)->only([
                                        'id',
                                        'user_id',
                                        'amount'
                                    ]);


                            $temp['name'] = $bet->user->name;
                            $temp['percentage'] = $commission_percentage;
                            $temp['earnigns'] = $bet->amount * $commission_percentage;

                            return $temp;
                        });
                        
                        $totalAmountBetted = $streamerBettorBets->sum('amount');
                        $earnings = $streamerBettorBets->sum('earnigns');

                        $commission = \App\CommissionsBets::create([
                            'match_id' => $match_id,
                            'date_settled' => $match_settled,
                            'amount' => $earnings,
                            'percentage' => $commission_percentage * 100,
                            'belongs_to' => $streamer->id,
                            'user_bets' => json_encode($streamerBettorBets),
                            'status' => 0,
                        ]);

                    }
                }
            }


        }        

//        $this->dispatch( new GenerateMatchBetsAffliatesCommissions($match) );

    }
}
