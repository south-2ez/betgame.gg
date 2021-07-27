<?php

use App\User;
use App\Report;
use App\MatchReport;
use App\Fee;
use App\Transaction;
use App\Partner;
use App\League;

/**
 * Get top 30 bettors
 * @return QueryBuilder $users
 */
function leaderBoard()
{
    $users = User::query()
    ->select('users.name','users.id',\DB::raw('COALESCE(SUM(b.amount),0)+users.credits total'),'bagde.badge_name')
    ->leftjoin(\DB::raw('(SELECT bets.* FROM bets JOIN matches on matches.id = bets.match_id where matches.status="open" or matches.status="ongoing" or bets.type = "tournament") b'),'b.user_id','=', 'users.id')
    ->leftjoin(\DB::raw('(SELECT badge_user.*, badges.name as badge_name FROM badge_user JOIN badges on badges.id = badge_user.badge_id ) bagde'),'bagde.user_id','=', 'users.id')
    ->where('users.type','!=','admin')
    ->where('bagde.badge_name','BETATESTER')
    ->groupBy('users.name','users.credits','users.id')
    ->orderBy('total','desc')
    ->limit(30);
    return $users;
}

function matchWinPercentagePerTeam($match_id, $team_id) {
    $total_bets = \App\Bet::where('match_id', $match_id)->sum('amount');
    $team_bets = \App\Bet::where('match_id', $match_id)->where('team_id', $team_id)->sum('amount');
    return $total_bets ? ($team_bets / $total_bets) * 100 : 0;
}

function tournamentWinPercentagePerTeam($league_id, $team_id) {
    $total_bets = \App\Bet::tournament($league_id)->sum('amount');
    $team_bets = \App\Bet::tournament($league_id)->where('team_id', $team_id)->sum('amount');
    $percentage =  $total_bets ? ($team_bets / $total_bets) * 100 : 0;
    return bcdiv($percentage, 1 ,2);
}

function tournamentRatioPerTeam($league_id, $team_id, $opt_amount = 0) {
    $bet_fee = \App\League::find($league_id)->betting_fee;
    $total_bets = \App\Bet::tournament($league_id)->sum('amount') + $opt_amount;
    $team_bets = \App\Bet::tournament($league_id)->where('team_id', $team_id)->sum('amount') + $opt_amount;
    $ratio =  $team_bets ? $total_bets / $team_bets * (1 - $bet_fee) : 0;
    return bcdiv($ratio, 1 ,2);
}

function potentialTournamentWinningPerUserPerTeam($league_id, $team_id, $user_id, $opt_amount = 0) {
    $user_bets = \App\Bet::tournament($league_id)->where('team_id', $team_id)->where('user_id', $user_id)->sum('amount') + $opt_amount;
    $team_bets = \App\Bet::tournament($league_id)->where('team_id', $team_id)->sum('amount') + $opt_amount;
    $total_team_payout = ($team_bets * tournamentRatioPerTeam($league_id, $team_id, $opt_amount));
    return $team_bets ? ($user_bets / $team_bets) * $total_team_payout : 0;
}

function currentMatches() {
    $_matches = \App\Match::all()->load('teamA', 'teamB')->sortBy('schedule');
    $currList = collect();
    $oldList = collect();
    foreach ($_matches as $_m) {
        if ($_m->schedule->isFuture()) {
            $currList->push($_m);
        } else {
            if ($_m->status == 'ongoing')
                $currList->push($_m);
            else
                $oldList->push($_m);
        }
    }
    return $currList->merge($oldList->sortByDesc('schedule'));
}

function setupOngoingMatch(\App\Match $match) {

    //get all user ids of admin and matchmanager
    $adminMMUsersIds = getAdminMatchManagersUserIds();
    $match_ids = []; //match ids that we need to remove admin bets
    $match_ids[] = $match->id;

    //generate key for locking bets
    disableBettingGoingLiveMatch($match->id);
    //end locking parent match

    // Remove admin bets from submatches
    
    // $gameGrpWhere = $match->type == 'main' ? '<=1' : ($match->sub_type == 'main' ? $match->game_grp : NULL);
    // $subMatches = !empty($gameGrpWhere) ? $match->subMatches->where('status','open')->where('game_grp',$gameGrpWhere) : NULL;

    $subMatches = $match->type == 'main' ? 
                        $match->subMatches->where('game_grp', '<=', 1)->where('status','open') : 
                        (
                            $match->sub_type == 'main' ?  
                                \App\SubMatch::where('main_match', $match->main_match)
                                    ->where('game_grp', $match->game_grp)
                                    ->where('id', '!=', $match->id)
                                    ->where('status','open')->get() : 
                                NULL
                        );
                        
    $subMatchesIds = !empty($subMatches) ? $subMatches->pluck('id') : [];
    // $match_ids = [$match->id, ...$subMatchesIds];
    $match_ids = array_merge($match_ids,$subMatchesIds->toArray());
    
    //proceed delete admin bets
    $adminBetsDelete =  \App\Bet::whereIn('user_id',$adminMMUsersIds)->whereIn('match_id', $match_ids)->get();

    foreach($adminBetsDelete as $adminBet){
        $adminBet->delete();
    }

    $matchBets = \App\Bet::where('match_id', $match->id)->whereNotIn('user_id', $adminMMUsersIds)->get();
    $matchRatio = calculateMatchTeamRatios($match, $matchBets);  

    //here we check if BND needs to bet to cover threshold 

    $shouldRecalculateRatio = bndAutoBetFromThreshold($match, $matchRatio);

    if($shouldRecalculateRatio){
        //re run query
        $matchBets = \App\Bet::where('match_id', $match->id)->get();
        $matchRatio = calculateMatchTeamRatios($match, $matchBets); 
    }

    //end bnd threshold

    $match->status = 'ongoing';
    $match->team_a_ratio = $matchRatio['team_a_ratio'];
    $match->team_b_ratio = $matchRatio['team_b_ratio'];
    $match->team_c_ratio = $matchRatio['team_c_ratio'];
    $match->date_set_live = Carbon::now();
    $match->save();

    //disable locking of betting on this match
    allowBettingGoingLiveMatch($match->id);
    //end locking parent match

    //after


    $updateMatchBetsRatio = DB::statement("
                                update bets set ratio = 
                                    CASE
                                        WHEN team_id = '". $match->team_a. "'
                                        THEN ". $matchRatio['team_a_ratio'] ."
                                        WHEN team_id = '". $match->team_b. "'
                                        THEN ". $matchRatio['team_b_ratio'] ."                                        
                                        ELSE ". $matchRatio['team_c_ratio'] ."
                                    END
                                WHERE match_id = '". $match->id. "'
                            ");

    if(!empty($subMatchesIds)){
        foreach($subMatches as $key => $subMatch){
            //generate key for locking bets
            disableBettingGoingLiveMatch($subMatch->id);
            //end locking match bets

            $subMatchBets = \App\Bet::where('match_id', $subMatch->id)->whereNotIn('user_id', $adminMMUsersIds)->get();
            $subMatchRatio = calculateMatchTeamRatios($subMatch, $subMatchBets);

            //here we check if BND needs to bet to cover threshold 

            $shouldRecalculateRatio = bndAutoBetFromThreshold($subMatch, $subMatchRatio);

            if($shouldRecalculateRatio){
                //re run query
                $subMatchBets = \App\Bet::where('match_id', $subMatch->id)->get();
                $subMatchRatio = calculateMatchTeamRatios($subMatch, $subMatchBets);
            }
            
            //end bnd threshold

            $subMatch->status = 'ongoing';
            $subMatch->team_a_ratio = $matchRatio['team_a_ratio'];
            $subMatch->team_b_ratio = $matchRatio['team_b_ratio'];
            $subMatch->team_c_ratio = $matchRatio['team_c_ratio'];
            $subMatch->date_set_live = Carbon::now();
            $subMatch->save();            

            $updateSubMatchBetsRatio = DB::statement("
                                        update bets set ratio = 
                                            CASE
                                                WHEN team_id = '". $subMatch->team_a. "'
                                                THEN ". $subMatchRatio['team_a_ratio'] ."
                                                WHEN team_id = '". $subMatch->team_b. "'
                                                THEN ". $subMatchRatio['team_b_ratio'] ."                                                
                                                ELSE ". $subMatchRatio['team_c_ratio'] ."
                                            END
                                        WHERE match_id = '". $subMatch->id. "'
                                    ");  
                                    
            //disable locking of betting on this match
            allowBettingGoingLiveMatch($subMatch->id);
            //end locking parent match
        }
    }



    return;
}

/**
 * Get top 30 bettors
 * @return Integer $total_circulating_credits
 */
function calculateCredits($type = null)
{
    $now = new \DateTime;
    $yesterday = $now->modify('-1 day')->format('Y-m-d');
    $yesterday_available_credits = Report::whereDate('date','=', $yesterday)->where('meta_key','total_available_credits')->first();
    $yesterday_circulating_credits = Report::whereDate('date','=', $yesterday)->where('meta_key','total_circulating_credits')->first();
    
    $adminMMUsersIds = User::whereIn('type',['admin','matchmanager'])->pluck('id');

    //$total_available_credits = User::query()->whereNotIn('users.type', ['admin', 'matchmanager'])->select(\DB::raw('sum(credits) as total'))->first()->total;
    $total_available_credits = User::select('credits')->whereNotIn('id',$adminMMUsersIds)->sum('credits');

    // $total_match_betted_credits = User::query()
    //     ->select('users.name','users.id',\DB::raw('SUM(b.amount) total'))
    //     ->leftjoin(\DB::raw('(SELECT bets.* FROM bets JOIN matches on matches.id = bets.match_id where ( matches.status="open" or matches.status="ongoing") and date(bets.created_at) > "2018-05-21" and bets.type = "matches") b'),'b.user_id','=', 'users.id')
    //     ->whereNotIn('users.type', ['admin', 'matchmanager'])
    //     ->groupBy('users.name','users.credits','users.id')
    //     ->get()
    //     ->sum('total');;

    $daysOlder = \Carbon::now()->subDays(30)->toDateTimeString();
    $daysOlder2 = \Carbon::now()->subDays(120)->toDateTimeString();
    $older7days = \Carbon::now()->subDays(7)->toDateTimeString();
    $openLiveMatches = \App\Match::select('id')->whereIn('status', ['open', 'ongoing'])->pluck('id');
    $matchBets = \App\Bet::select('amount','type')->whereIn('match_id', $openLiveMatches)
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->where('created_at', '>', $daysOlder)
        ->get();

    $total_match_betted_credits = $matchBets->where('type', 'matches')->sum('amount');    

    $total_match_fees_collected = MatchReport::query()
        ->whereDate('created_at','>','2018-05-21')
        ->select(\DB::raw('sum(total_fees_collected) as total'))
        ->first()->total;
    $total_fees_collected = Fee::query()->select(\DB::raw('SUM(collected) as total'))->first()->total;
    $total_league_fees_collected = Fee::query()->where('meta_key','tournament')->select(\DB::raw('SUM(collected) as total'))->first()->total;
    $total_cashout_fees_collected = Fee::query()->whereIn('meta_key',['cashout','cashout-partner'])->select(\DB::raw('SUM(collected) as total'))->first()->total;
  
  
    // $total_league_betted_credits = User::query()
    //     ->select('users.name','users.id',\DB::raw('SUM(b.amount) total'))
    //     ->leftjoin(\DB::raw('(SELECT bets.* FROM bets JOIN leagues on leagues.id = bets.league_id where leagues.league_winner=0 and date(bets.created_at) > "2018-06-19" and bets.type = "tournament") b'),'b.user_id','=', 'users.id')
    //     ->whereNotIn('users.type', ['admin', 'matchmanager'])
    //     ->groupBy('users.name','users.credits','users.id')
    //     ->get()
    //     ->sum('total');

    $openLiveLeagues = \App\League::select('id')->where('league_winner', 0)->pluck('id');

    $leagueBets = \App\Bet::select('amount')->whereIn('league_id', $openLiveLeagues)
    ->whereNotIn('user_id',$adminMMUsersIds)
    ->whereDate('created_at', '>', $daysOlder2)
    ->where('type', 'tournament')
    ->get();

    $total_league_betted_credits = $leagueBets->sum('amount');

    $total_pending_cashouts = Transaction::query()
        ->where(['type' => 'cashout', 'status' => 'processing'])
        ->whereDate('created_at','>','2018-05-21')
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;


    $total_approved_cashouts_yesterday = Transaction::query()
        ->where(['type' => 'cashout', 'status' => 'completed'])
        ->whereDate('approved_rejected_date',$yesterday)
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

        
    $total_approved_cashouts_today = Transaction::query()
        ->where(['type' => 'cashout', 'status' => 'completed'])
        ->whereDate('approved_rejected_date',date('Y-m-d'))
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_pending_cashouts_via_partner_total =  \App\PartnerTransaction::query()
        ->where(['type' => 'cashout', 'status' => 0, 'trade_type' => 'partner-user'])
        ->whereDate('created_at','>','2018-05-21')
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_approved_cashouts_yesterday_via_partner =  \App\PartnerTransaction::query()
        ->where(['type' => 'cashout', 'status' => 1, 'trade_type' => 'partner-user'])
        ->whereDate('approved_rejected_date',$yesterday)
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_approved_cashouts_today_via_partner =  \App\PartnerTransaction::query()
        ->where(['type' => 'cashout', 'status' => 1, 'trade_type' => 'partner-user'])
        ->whereDate('approved_rejected_date',date('Y-m-d'))
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;


    
    $total_pending_deposits_via_direct_total = Transaction::query()
        ->where(['type' => 'deposit', 'status' => 'processing'])
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->whereDate('created_at','>','2018-05-21')
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_approved_deposits_yesterday = Transaction::query()
        ->where(['type' => 'deposit', 'status' => 'completed'])
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->whereDate('approved_rejected_date', $yesterday)
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_approved_deposits_today = Transaction::query()
        ->where(['type' => 'deposit', 'status' => 'completed'])
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->whereDate('approved_rejected_date',date('Y-m-d'))
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;


    $total_pending_deposits_via_partner_total = \App\PartnerTransaction::query()
        ->where(['type' => 'deposit', 'status' => 0, 'trade_type' => 'partner-user'])
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->whereDate('created_at','>','2018-05-21')
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_approved_deposits_yesterday_via_partner =  \App\PartnerTransaction::query()
        ->where(['type' => 'deposit', 'status' => 1, 'trade_type' => 'partner-user'])
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->whereDate('approved_rejected_date',$yesterday)
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_approved_deposits_today_via_partner =  \App\PartnerTransaction::query()
        ->where(['type' => 'deposit', 'status' => 1, 'trade_type' => 'partner-user'])
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->whereDate('approved_rejected_date',date('Y-m-d'))
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;        


    $total_partners_credits = Partner::query()
        ->select(\DB::raw('sum(partner_credits) as total'))
        ->first()->total;
    $number_of_users_who_have_credits = User::query()->where('credits','>', 0.00)->whereNotIn('users.type', ['admin', 'matchmanager'])->select(\DB::raw('COUNT(*) as total'))->first()->total;
    $users_with_greater_than_100 = User::query()->where('credits','>',100.00)->whereNotIn('users.type', ['admin', 'matchmanager'])->select(\DB::raw('COUNT(*) as total'))->first()->total;
    // $number_of_users_who_have_betted_in_the_last_7_days = User::query()
    //     ->select('users.*')
    //     ->join(\DB::raw('bets as b'), function ($join){
    //         $join->on('b.user_id', '=', 'users.id')
    //             ->on('b.created_at', '>=', \DB::raw('NOW() + INTERVAL -7 DAY'))
    //             ->on('b.created_at', '<', \DB::raw('NOW() + INTERVAL 0 DAY'));
                
    //     })
    //     ->whereNotIn('users.type', ['admin', 'matchmanager'])
    //     ->distinct()
    //     ->count();
    $number_of_users_who_have_betted_in_the_last_7_days = \App\Bet::select('user_id')->whereNotIn('id',$adminMMUsersIds)->where('created_at','>=', $older7days)->distinct()->count();
    $average_amount_of_credits_for_users_who_have_credits = $total_available_credits/$users_with_greater_than_100;
    //$total_circulating_credits = $total_available_credits+$total_match_betted_credits+$total_league_betted_credits+$total_pending_cashouts+$total_partners_credits;
    $total_circulating_credits = $total_available_credits+$total_match_betted_credits+$total_league_betted_credits+$total_pending_cashouts+$total_partners_credits+$total_pending_cashouts_via_partner_total;
    $data['total_circulating_credits'] = number_format($total_circulating_credits,2);
    $data['yesterday_circulating_credits'] = $yesterday_circulating_credits ? number_format($yesterday_circulating_credits->data->total,2) : 'no data';
    $data['difference_circulating_credits'] =  $yesterday_circulating_credits ? number_format($total_circulating_credits - $yesterday_circulating_credits->data->total,2) : number_format($total_circulating_credits,2);
    $data['total_available_credits'] = number_format($total_available_credits+$total_partners_credits,2);
    $data['yesterday_available_credits'] = $yesterday_available_credits ? number_format($yesterday_available_credits->data->total,2) : 'no data';
    $data['difference_available_credits'] =  $yesterday_available_credits ? number_format($total_available_credits - $yesterday_available_credits->data->total,2) : number_format($total_available_credits,2);
    $data['total_match_betted_credits'] = number_format($total_match_betted_credits,2);
    $data['total_league_betted_credits'] = number_format($total_league_betted_credits,2);
    $data['total_betted_credits'] = number_format($total_match_betted_credits+$total_league_betted_credits,2);

    if(checkUser()){
        $data['total_match_fees_collected'] = number_format($total_match_fees_collected,2);
        $data['total_league_fees_collected'] = number_format($total_league_fees_collected,2);
        $data['total_cashout_fees_collected'] = number_format($total_cashout_fees_collected,2);
        $data['total_fees_collected'] = number_format($total_fees_collected,2);
    }
    $data['total_partners_credits'] = number_format($total_partners_credits,2);
    $data['number_of_users_who_have_credits'] = $users_with_greater_than_100;
    $data['number_of_users_who_have_betted_in_the_last_7_days'] = $number_of_users_who_have_betted_in_the_last_7_days;
    $data['average_amount_of_credits_for_users_who_have_credits'] = number_format($average_amount_of_credits_for_users_who_have_credits,2);

    //cashouts
    $data['total_pending_cashouts_via_direct_total'] = number_format($total_pending_cashouts,2);
    $data['total_approved_cashouts_yesterday'] = number_format($total_approved_cashouts_yesterday, 2);
    $data['total_approved_cashouts_today'] = number_format($total_approved_cashouts_today,2);
    $data['total_pending_cashouts_via_partner_total'] = number_format($total_pending_cashouts_via_partner_total,2);
    $data['total_approved_cashouts_yesterday_via_partner'] = number_format($total_approved_cashouts_yesterday_via_partner,2);
    $data['total_approved_cashouts_today_via_partner'] = number_format($total_approved_cashouts_today_via_partner,2);

    //deposits
    $data['total_pending_deposits_via_direct_total'] = number_format($total_pending_deposits_via_direct_total,2);
    $data['total_approved_deposits_yesterday'] = number_format($total_approved_deposits_yesterday,2);
    $data['total_approved_deposits_today'] = number_format($total_approved_deposits_today,2);
    $data['total_pending_deposits_via_partner_total'] = number_format($total_pending_deposits_via_partner_total,2);
    $data['total_approved_deposits_yesterday_via_partner'] = number_format($total_approved_deposits_yesterday_via_partner,2);
    $data['total_approved_deposits_today_via_partner'] = number_format($total_approved_deposits_today_via_partner,2);

    

    return $type ? $data[$type] : $data;
}

function checkUser(){
    $users = [596,5,1,238];
    if(\Auth::user()){
        return in_array(\Auth::user()->id, $users);
    }else{
        return true;
    }
}

function getCirculatingCredits() {
    return getCirculatingCreditsV2();
    $total_available_credits = \DB::table('users')->whereNotIn('type', ['admin', 'matchmanager'])->sum('credits');
    
    $total_match_betted_credits = \DB::table('bets')
        ->join('users', 'bets.user_id', '=', 'users.id')
        ->join('matches', 'bets.match_id', '=', 'matches.id')
        ->where('bets.type', 'matches')
        ->whereNotIn('users.type', ['admin', 'matchmanager'])
        ->whereIn('matches.status', ['open', 'ongoing'])
        ->whereRaw('date(bets.created_at) > "2018-06-19"')
        ->sum('bets.amount');

    $total_league_betted_credits = \DB::table('bets')
        ->join('users', 'bets.user_id', '=', 'users.id')
        ->join('leagues', 'bets.league_id', '=', 'leagues.id')
        ->where('bets.type', 'tournament')
        ->where('leagues.league_winner', 0)
        ->whereNotIn('users.type', ['admin', 'matchmanager'])
        ->whereDate('bets.created_at', '>', '2018-06-19')
        ->sum('bets.amount');

    $total_pending_cashouts = Transaction::where(['type' => 'cashout', 'status' => 'processing'])
        ->whereDate('created_at','>','2018-05-21')
        ->sum('amount');

    $total_partners_credits = Partner::sum('partner_credits');

    $total_circulating_credits = $total_available_credits+$total_match_betted_credits+$total_league_betted_credits+$total_pending_cashouts+$total_partners_credits;
    return number_format($total_circulating_credits,2);
}

function getCirculatingCreditsV2(){

    $adminMMUsersIds = \App\User::select('id')->whereIn('type', ['admin','matchmanager'])->pluck('id');
    $total_available_credits = \App\User::select('credits')->whereNotIn('id', $adminMMUsersIds)->sum('credits');
    $openLiveMatches = \App\Match::select('id')->whereIn('status', ['open', 'ongoing'])->pluck('id');
    $openLiveLeagues = \App\League::select('id')->where('league_winner', 0)->pluck('id');
    $daysOlder = \Carbon::now()->subDays(30)->toDateTimeString();

    $matchBets = \App\Bet::select('amount','type')->whereIn('match_id', $openLiveMatches)
        ->whereNotIn('user_id', $adminMMUsersIds)
        ->where('created_at', '>', $daysOlder)
        ->get();

    $total_match_betted_credits = $matchBets->where('type', 'matches')->sum('amount');

    $leagueBets = \App\Bet::select('amount')->whereIn('league_id', $openLiveLeagues)
        ->whereNotIn('user_id',$adminMMUsersIds)
        ->whereDate('created_at', '>', $daysOlder)
        ->where('type', 'tournament')
        ->get();

    $total_league_betted_credits = $leagueBets->sum('amount');

    $total_pending_cashouts = Transaction::where(['type' => 'cashout', 'status' => 'processing'])
        ->whereDate('created_at','>','2018-05-21')
        ->sum('amount');

    $total_pending_cashouts_via_partner =  \App\PartnerTransaction::query()
        ->where(['type' => 'cashout', 'status' => 0, 'trade_type' => 'partner-user'])
        ->select(\DB::raw('sum(amount) as total'))
        ->first()->total;

    $total_partners_credits = Partner::sum('partner_credits');

    $total_circulating_credits = $total_available_credits+$total_match_betted_credits+$total_league_betted_credits+$total_pending_cashouts+$total_partners_credits+$total_pending_cashouts_via_partner;
    return number_format($total_circulating_credits,2);

}

function hasMatchManagementAccess(\App\User $user)
{
    return $user->isAdmin() || $user->isMatchManager();
}

function hasPartnerDashboardAccess(\App\User $user)
{
    if(empty($user)) return false;

    if($user->isAgent()){
        return ($user->userPartner && $user->userPartner->verified == 1 && $user->userPartner->active == 1);
    }else{
        return isPartnerSubUser($user) && !empty( $user->subUserPartner ) ? $user->subUserPartner->partner->verified == 1 && $user->subUserPartner->partner->active == 1 : false;
    }
 
}

function isPartnerSubUser(\App\User $user)
{
    
    $userBadgesCacheKey = 'userBadges_' . getUserCacheKey($user);
    $user_badges = Cache::remember( $userBadgesCacheKey, 120 , function () use ($user){

        return $user->badges;
    
    });
    
    return !empty( $user ) ? in_array('PARTNER SUB-USER', $user_badges->pluck('name')->toArray()) : false;
}
/**
 * Get League reports
 * @param int league_id
 * @return html table of reports
 */
function returnLeagueReport($league_id,$type)
{
    $league = League::find($league_id);
    $bets = $league->tournamentBets->load('user');
    $data['total_bettors'] = $bets->count();
    $data['total_bets'] = $bets->sum('amount');
    $data['total_admin_bets'] = 0;
    $data['average_bets'] = $data['total_bettors'] != 0 ? $data['total_bets']/$data['total_bettors'] : 0.00;
    $data['betting_fee'] = $league->betting_fee;
    $data['circulating_credits_before_settled'] = $league->circulating_credits_before_settled;
    $data['circulating_credits_after_settled'] = $league->circulating_credits_after_settled;
    $div = '';
    $content = '';
    $div .= '<div class="m-container1" style="width: 98% !important;">';
    $div .= '<div class="main-ct" style="margin-bottom: 0">';
    $div .= '<div class="title">Admin Report</div>';
    $div .= '<div class="clearfix"></div>';
    $div .= '<div class="blk-1">';
    $div .= '<div class="col-md-12" style="max-height: 500px; overflow: auto">';
    $div .= '<ul class="nav nav-tabs" role="tablist">';
    $div .= '<li role="presentation" class="active"><a href="#dasboard" aria-controls="dasboard" role="tab" data-toggle="tab">Dashboard</a></li>';
    foreach($league->teams as $team){
        $data['data'][$team->id] = [];
        $data['data'][$team->id]['name'] = $team->name;
        $data['data'][$team->id]['total_bettors'] = 0;  
        $data['data'][$team->id]['total_bets'] = 0;
        $data['data'][$team->id]['total_profit'] = 0;
        $data['data'][$team->id]['users'] = [];
        $data['data'][$team->id]['total_admin_bets'] = 0;
        $color = 'black';
        $table = '';
        $div .= '<li role="presentation"><a href="#'.$team->id.'" aria-controls="'.$team->name.'" role="tab" data-toggle="tab">'.$team->name.'</a></li>';
        $table .= '<table id="table-'.$team->id.'" class="table table-striped" width="100%">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>ID</th>';
        $table .= '<th>Bettors</th>';
        $table .= '<th>Bet Amount</th>';
        $table .= '<th>Profit/Loss</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach ($bets as $bet) {
            if($team->id == $bet->team_id){
                if($bet->user->type == 'user'){
                    $style= '';
                }else{
                    $style = 'style="background:#bae6ff;"';
                    $data['data'][$team->id]['total_admin_bets']  += $bet->amount;
                    $data['total_admin_bets'] += $bet->amount;
                }

                $table .= '<tr '.$style.'>';
                $table .='<td>'.$bet->user->id.'</td>';
                $table .='<td>'.$bet->user->name.'</td>';
                $data['data'][$team->id]['total_bettors'] += 1;
                $data['data'][$team->id]['total_bets'] += $bet->amount;
                $profit = potentialTournamentWinningPerUserPerTeam($league_id, $bet->team_id, $bet->user->id);
                $table .= '<td>&#8369; '.number_format($bet->amount,2).'</td>';
                if($league->league_winner){
                    if($league->league_winner == $bet->team_id){
                        $data['data'][$team->id]['total_profit'] += $profit;
                        $color='green';
                        $table .= '<td><span style="color:green">'.number_format($profit,2).'</span></td>';
                        $user = array_merge($bet->user->toArray(),['amount' => $bet->amount,'profit' => $profit]);
                        $data['data'][$team->id]['users'][] = $user;
                    }else{
                        $data['data'][$team->id]['total_profit'] += $bet->amount;
                        $color='red';
                        $table .= '<td><span style="color:red">'.number_format($bet->amount,2).'</span></td>';
                        $user = array_merge($bet->user->toArray(),['amount' => $bet->amount,'profit' => $profit]);
                        $data['data'][$team->id]['users'][] = $user;
                    }
                }else{
                    $color='black';
                    $table .= '<td></td>';
                    $user = array_merge($bet->user->toArray(),['amount' => $bet->amount,'profit' => $profit]);
                    $data['data'][$team->id]['users'][] = $user;

                }
                $table .= '</tr>';
            }
        }
        if($league->league_winner){
            if($league->league_winner == $team->id){
                $data['data'][$team->id]['status'] = 'win';
            }else{
                $data['data'][$team->id]['status'] = 'lost';
            }
        }else{  
            $data['data'][$team->id]['status'] = 'ongoing';
        }
        $data['data'][$team->id]['average_bets'] =  $data['data'][$team->id]['total_bettors'] != 0 ? $data['data'][$team->id]['total_bets']/$data['data'][$team->id]['total_bettors'] : 0.00;
        $table .= '<tr>';
        $table .= '<td></td>';
        $table .= '<td></td>';
        $table .= '<td style="font-weight: bold">'.number_format($data['data'][$team->id]['total_bets'],2).'</td>';
        $table .= '<td style="font-weight: bold"><span style="color:'.$color.'">'.($data['data'][$team->id]['total_profit'] != 0 ? number_format($data['data'][$team->id]['total_profit'],2) : '').'</span></td>';
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '</table>';
        $content .= '<div role="tabpanel" class="tab-pane fade in" id="'.$team->id.'">';
        $content .= '<div class="tab-content" style="margin-top: 20px">';
        $content .= '<div class="col-md-12" style="max-height: 500px; overflow: auto">';
        $content .= $table;
        $content .= '</div>';
        $content .= '<dt class="col-sm-5">Total Bettors</dt>';
        $content .= '<dd class="col-sm-7">'.$data['data'][$team->id]['total_bettors'].'</dd>';
        $content .= '<dt class="col-sm-5">Total Bets (with Admin Bets)</dt>';
        $content .= '<dd class="col-sm-7">'.number_format($data['data'][$team->id]['total_bets'],2).'</dd>';
        $content .= '<dt class="col-sm-5">Total Bets (Real Bets no Admin Bets)</dt>';
        $content .= '<dd class="col-sm-7">'.number_format( ($data['data'][$team->id]['total_bets']-$data['data'][$team->id]['total_admin_bets']) ,2).'</dd>';
        $content .= '<dt class="col-sm-5">Average Bets</dt>';
        $content .= '<dd class="col-sm-7">'.number_format($data['data'][$team->id]['average_bets'],2).'</dd>';
        $content .= '</div>';
        $content .= '</div>';
    }
    $data['total_fees_collected'] = $league->league_winner ? $data['total_bets']*$league->betting_fee : 0.00;
    $data['total_payout'] = $league->league_winner ? $data['total_bets']-$data['total_fees_collected'] : 0.00;
    $content .= '<div role="tabpanel" class="tab-pane fade in active" id="dasboard">';
    $content .= '<div class="tab-content" style="margin-top: 20px">';
    $content .= '<dt class="col-sm-5">Total Bettors on this league</dt>';
    $content .= '<dd class="col-sm-7">'.$data['total_bettors'].'</dd>';
    $content .= '<dt class="col-sm-5">Total Bets on this league (with Admin Bets)</dt>';
    $content .= '<dd class="col-sm-7">'.number_format($data['total_bets'] ,2).'</dd>';
    $content .= '<dt class="col-sm-5">Total Bets on this league (Real Bets no Admin Bets)</dt>';
    $content .= '<dd class="col-sm-7">'.number_format( ( $data['total_bets'] - $data['total_admin_bets'] ) ,2).'</dd>';    
    $content .= '<dt class="col-sm-5">Average Bets on this league</dt>';
    $content .= '<dd class="col-sm-7">'.number_format($data['average_bets'],2).'</dd>';
    $content .= '<dt class="col-sm-5">Match Fee</dt>';
    $content .= '<dd class="col-sm-7">'.($data['betting_fee']*100).'%</dd>';
    $content .= '<dt class="col-sm-5">Total Fees collected on this league</dt>';
    $content .= '<dd class="col-sm-7">'.number_format($data['total_fees_collected'],2).'</dd>';
    $content .= '<dt class="col-sm-5">Total Payouts</dt>';
    $content .= '<dd class="col-sm-7">'.number_format($data['total_payout'],2).'</dd>';
    $content .= '<dt class="col-sm-5">Total Circulating Credits (Before):</dt>';
    $content .= '<dd class="col-sm-7">'. $data['circulating_credits_after_settled'] ?? '-' .'</dd>'; //circulating_credits_before_settled
    $content .= '<dt class="col-sm-5">Total Circulating Credits (After):</dt>';
    $content .= '<dd class="col-sm-7">'. $data['circulating_credits_before_settled'] ?? '-' .'</dd>'; //circulating_credits_after_settled
    $content .= '</div>';
    $content .= '</div>';
    $div .= '</ul>';
    $div .= '<div class="tab-content">';
    $div .= $content;
    $div .= '</div>';
    $div .= '</div>';
    $div .= '</div>';
    $div .= '</div>';
    $div .= '</div>';
    switch ($type) {
        case 'data':
            return $data;
            break;
        case 'div':
            return $div;
            break;
        
        default:
            return 'Oops!';
            break;
    }
}

function getAdminMatchManagersUserIds(){
    return \App\User::select('id')->whereIn('type', ['admin','matchmanager'])->pluck('id');
}

function calculateMatchTeamRatios($match, $bets){
    if( !empty($match) && !empty($bets)){
        $total_bets = $bets->sum('amount');
        $team_a_bets = $bets->where('team_id', $match->team_a)->sum('amount');
        $team_b_bets = $bets->where('team_id', $match->team_b)->sum('amount');
        $team_c_bets = $bets->where('team_id', $match->team_c)->sum('amount');
        
        $team_a_ratio =  $team_a_bets > 0 ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;
        $team_b_ratio = $team_b_bets > 0 ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;
        $team_c_ratio = $team_c_bets > 0 ? $total_bets / $team_c_bets * (1 - $match->fee) : 0;
        
        $team_a_ratio  = bcdiv($team_a_ratio, 1 ,2);
        $team_b_ratio  = bcdiv($team_b_ratio, 1 ,2);
        $team_c_ratio  = bcdiv($team_c_ratio, 1 ,2);

        $team_a_percentage = $total_bets > 0 ? ($team_a_bets / $total_bets) * 100 : 0;
        $team_b_percentage = $total_bets > 0 ? ($team_b_bets / $total_bets) * 100 : 0;
        $team_c_percentage = $total_bets > 0 ? ($team_c_bets / $total_bets) * 100 : 0;

        return [
            'team_a_ratio' => $bets->count() ? $team_a_ratio : 2,
            'team_b_ratio' => $bets->count() ? $team_b_ratio : 2,
            'team_c_ratio' => $bets->count() ? $team_c_ratio : 2,
            'team_a_percentage' => $bets->count() ? $team_a_percentage : 50,
            'team_b_percentage' => $bets->count() ? $team_b_percentage : 50,
            'team_c_percentage' => $bets->count() ? $team_c_percentage : 0,
            'team_a_bets' => $team_a_bets,
            'team_b_bets' => $team_b_bets,
            'team_c_bets' => $team_c_bets,
            'total_bets' => $total_bets,
        ];
    }

    return [
        'team_a' => 2,
        'team_b' => 2,
        'team_c' => 0
    ];
}

function calculateRealMatchTeamRatios($match, $bets){
    if( !empty($match) && !empty($bets)){
        $adminMMUsersIds = getAdminMatchManagersUserIds();

        $bets = $bets->whereNotIn('user_id', $adminMMUsersIds);

        $total_bets = $bets->sum('amount');
        $team_a_bets = $bets->where('team_id', $match->team_a)->sum('amount');
        $team_b_bets = $bets->where('team_id', $match->team_b)->sum('amount');
        $team_c_bets = $bets->where('team_id', $match->team_c)->sum('amount');
        
        $team_a_ratio =  $team_a_bets > 0 ? $total_bets / $team_a_bets * (1 - $match->fee) : 0;
        $team_b_ratio = $team_b_bets > 0 ? $total_bets / $team_b_bets * (1 - $match->fee) : 0;
        $team_c_ratio = $team_c_bets > 0 ? $total_bets / $team_c_bets * (1 - $match->fee) : 0;
        
        $team_a_ratio  = bcdiv($team_a_ratio, 1 ,2);
        $team_b_ratio  = bcdiv($team_b_ratio, 1 ,2);
        $team_c_ratio  = bcdiv($team_c_ratio, 1 ,2);

        $team_a_percentage = $total_bets > 0 ? ($team_a_bets / $total_bets) * 100 : 0;
        $team_b_percentage = $total_bets > 0 ? ($team_b_bets / $total_bets) * 100 : 0;
        $team_c_percentage = $total_bets > 0 ? ($team_c_bets / $total_bets) * 100 : 0;

        return [
            'team_a_ratio' => $bets->count() ? $team_a_ratio : 2,
            'team_b_ratio' => $bets->count() ? $team_b_ratio : 2,
            'team_c_ratio' => $bets->count() ? $team_c_ratio : 0,
            'team_a_percentage' => $bets->count() ? $team_a_percentage : 50,
            'team_b_percentage' => $bets->count() ? $team_b_percentage : 50,
            'team_c_percentage' => $bets->count() ? $team_c_percentage : 0,
            'team_a_bets' => $team_a_bets,
            'team_b_bets' => $team_b_bets,
            'team_c_bets' => $team_c_bets,
            'total_bets' => $total_bets,
        ];
    }

    return [
        'team_a' => 2,
        'team_b' => 2,
        'team_c' => 0,
    ];
}

function getUserCacheKey($user = null){
    $user = !empty($user) ? $user : (\Auth::check() ? \Auth::user() : null);

    return !empty($user) ? $user->id .'_'. $user->getRememberToken() : null;
}

function getSuperAdminIds(){
    return !empty(env('SUPER_ADMINS')) ? explode(',', env('SUPER_ADMINS'))  : [];
}

function compareByTimeStamp($a, $b ){
    return strtotime($b->schedule) - strtotime($a->schedule);
}

function getCurrentDbConfigName(){
    return \DB::connection()->getConfig()['name'];
}

function generateLockKey(){
    return str_random(50) . getUserCacheKey();
}

function bndAutoBetFromThreshold($match, $matchRatio = null){
    
    $shouldRecalculateRatio = false;

    if($match->team_a_threshold_percent == 0 || $match->team_a_max_threshold_percent == 0 ||$match->team_b_threshold_percent == 0 || $match->team_b_max_threshold_percent == 0 ){
        return $shouldRecalculateRatio;
    }

    /**
     * 
     * Changes - using scenario switches to cleaner code
     *  Scenarios: 
     *  1. below/lower team_a min %
     *  2. above/higher team_a max %
     *  3. below/lower team_b min %
     *  4. above/higher team_b max %
     * 
     * 
     * //check if bnd should follow team_a bnd settings 
     * - Check if team_a [AO]% is less than team_a_MIN [BND SETTINGS]  //LOWER_TEAM_A_MIN
     * - Check if team_a [AO]% is greather than team_a_MAX [BND SETTINGS] //HIGHER_TEAM_A_MAX
     * //check if bnd should follow team_b bnd settings
     * - Check if team_b [AO]% is less than team_b_MIN [BND SETTINGS] //LOWER_TEAM_B_MIN
     * - Check if team_b [AO]% is greather than team_b_MAX [BND SETTINGS]  //HIGHER_TEAM_B_MAX
     */

    $team_a_bets = $matchRatio['team_a_bets'];
    $team_b_bets = $matchRatio['team_b_bets'];

    $team_a_min = $match->team_a_threshold_percent;
    $team_a_max = $match->team_a_max_threshold_percent;
    $team_b_min = $match->team_b_threshold_percent;
    $team_b_max = $match->team_b_max_threshold_percent;

    $team_a_min_percent =  $match->team_a_threshold_percent / 100;
    $team_a_max_percent =  $match->team_a_max_threshold_percent / 100;
    $team_b_min_percent =  $match->team_b_threshold_percent / 100;
    $team_b_max_percent =  $match->team_b_max_threshold_percent / 100;    

    // $scenario = $matchRatio['team_a_percentage'] < $team_a_min ? 'LOWER_TEAM_A_MIN' : 
    //             ( $matchRatio['team_a_percentage'] >  $team_a_max ? 'HIGHER_TEAM_A_MAX' : 
    //                 ( 
    //                     $matchRatio['team_b_percentage'] < $team_b_min ? 'LOWER_TEAM_B_MIN' : 
    //                         ( $matchRatio['team_b_percentage'] >  $team_b_max ? 'HIGHER_TEAM_B_MAX' : 'IN_RAGE' ) 
    //                 ) 
    //             ) ;
                
    $scenario = $matchRatio['team_a_percentage'] < $team_a_min ? 'LOWER_TEAM_A_MIN' : 
                    (  $matchRatio['team_b_percentage'] < $team_b_min ? 'LOWER_TEAM_B_MIN' :  'IN_RAGE' );
    
    switch($scenario){
        case 'LOWER_TEAM_A_MIN':
        // case 'HIGHER_TEAM_B_MAX':
        /**
         * - BND needs to bet on team_a
         * - BND needs to bet an amount to match min% of team_a
         */
        
        $maxOverAllTotal = $team_b_bets / $team_b_max_percent; // x
        $bndTeamBetsTotal = $maxOverAllTotal - $team_b_bets; //y
        $bndWillBetThisAmount = $bndTeamBetsTotal - $team_a_bets; //z

        $bnd_team_id = $match->team_a; //- BND needs to bet on team_a

        $shouldRecalculateRatio = $bndWillBetThisAmount > 0.01;    
        
        break;

        // case 'HIGHER_TEAM_A_MAX':
        case 'LOWER_TEAM_B_MIN':    
        /**
         * - BND needs to bet on team_b 
         * - BND needs to bet an amount to match min% of team_b
         */
        
        $maxOverAllTotal = $team_a_bets / $team_a_max_percent; // x
        $bndTeamBetsTotal = $maxOverAllTotal - $team_a_bets; //y
        $bndWillBetThisAmount = $bndTeamBetsTotal - $team_b_bets; //z

        $bnd_team_id = $match->team_b; //- BND needs to bet on team_b 
        
        $shouldRecalculateRatio = $bndWillBetThisAmount > 0.01;   
        

        break;


        default: 
            $shouldRecalculateRatio = false;
        break;

    }

     /** END - Changes - using scenario switches to cleaner code  */


    if($shouldRecalculateRatio){ //place bet

        $bndUser = \App\User::find(env('BND_MAIN_USER_ID',1066));

        $bndBet = new \App\Bet;
        $bndBet->user_id = $bndUser->id;
        $bndBet->type = 'matches';
        $bndBet->team_id = $bnd_team_id;
        $bndBet->amount = $bndWillBetThisAmount;
        $bndBet->match_id = $match->id;
        $bndBet->league_id = $match->league_id;
        $bndBet->save();

        $bndUser->decrement('credits', $bndWillBetThisAmount);
        $bndUser->touch();  
    
    }

    return $shouldRecalculateRatio;

}

function generateMatchGoingLiveKey($match_id = 0){
    return 'matchAboutToGoLiveLockKey_' . $match_id;
}

function disableBettingGoingLiveMatch($match_id){
    $cacheKey = generateMatchGoingLiveKey($match_id);
    Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);
    return true;
}

function allowBettingGoingLiveMatch($match_id){
    $cacheKey = generateMatchGoingLiveKey($match_id);
    Cache::store('redis_svr03')->forget($cacheKey);
    return true;
}

function isMatchBettingAllowed($match_id){
    
    $cacheKey = generateMatchGoingLiveKey($match_id);
    $isLocked = Cache::store('redis_svr03')->has($cacheKey);
    return !$isLocked;

}