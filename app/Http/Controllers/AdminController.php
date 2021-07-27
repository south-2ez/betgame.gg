<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Carbon\Carbon;

use App\TopBettor;
use App\ReportedBug;
use App\Promotion;
use App\MatchReport;
use App\Team;
use App\Transaction;
use App\User;
use App\TransactionNote;
use App\Partner;
use App\Province;
use App\Product;
use App\TransactionDiscrepancy;
use Validator;
use Image;
use App\Report;
use App\Commission;
use App\CommissionsPartner;
use App\CommissionsBets;
use App\Referal;
use App\Fee;
use App\Donation;
use App\Payout;
use App\Wallet;

class AdminController extends Controller
{


    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function index()
    {
        $badge_list = \App\Badge::all();

        $users = \App\User::all();
        $partners = Partner::all();
        $provinces = Province::all();
        $tos = \App\LegalDocument::where('type', 'tos')->first();
        $settings = [
            'bdo-account-name' => '',
            'bdo-account-number' => '',
            'bpi-account-name' => '',
            'bpi-account-number' => '',
            'metro-account-name' => '',
            'metro-account-number' => '',
            'remittance-name' => '',
            'remittance-number' => '',
            'remittance-location' => '',
            'coins-wallet-address' => '',
            'security-account-name' => '',
            'security-account-number' => ''
        ];

        $bankSettings = \App\SiteSetting::get();


        foreach($bankSettings->where('status',1) as $siteSettings) {
            if(isset($settings[$siteSettings->name])) {
                $settings[$siteSettings->name] = $siteSettings->value;
            }
        }
        
        $bdoAccounts =  \App\SiteSetting::where('name',"like","bdo-account-alias")->where('account_key', '!=', 'old')->get()->groupBy('account_key');
        $bpiAccounts =  \App\SiteSetting::where('name',"like","bpi-account-alias")->where('account_key', '!=', 'old')->get()->groupBy('account_key');
        $metroAccounts =  \App\SiteSetting::where('name',"like","metro-account-alias")->where('account_key', '!=', 'old')->get()->groupBy('account_key');
        $securityAccounts =  \App\SiteSetting::where('name',"like","security-account-alias")->where('account_key', '!=', 'old')->get()->groupBy('account_key');

        $bankAccounts = [
            'bdo' => $bdoAccounts,
            'bpi' => $bpiAccounts,
            'metro' => $metroAccounts,
            'security' => $securityAccounts
        ];

        return view('admin.dashboard', compact('badge_list', 'users', 'partners', 'provinces', 'settings', 'tos', 'bankAccounts', 'bankSettings'));
    }

    public function fetchOpenMatches()
    {
        return \App\Match::openMatches()->get();
    }
    
    public function showMatchDetail(\App\Match $match)
    {
        return $match->exists ? $match->load('teamA','teamB') : [];
    }
    
    public function getAllUsers(Request $request)
    {
        return Datatables::of(
            \App\User::with(
                [
                    'roles',
                    'badges',
                    'verified_by',
                    // 'unpaid_commissions.transaction' => function($q){
                    //          $q->where('transactions.status','completed');
                    //     },
                    // 'unpaid_partner_commissions.transaction' => function($q){
                    //         $q->where('status',1);
                    //    },
                    'unpaid_bets_commissions:belongs_to,amount,date_settled',
                ]
            ))->make(true);
    }
    
    public function setSiteSettings(Request $request) {


        // $validator = \Validator::make($request->all(), [
        //     'bdo-account-name' => 'required',
        //     'bdo-account-number' => 'required',
        //     'bpi-account-name' => 'required',
        //     'bpi-account-number' => 'required',
        //     'metro-account-name' => 'required',
        //     'metro-account-number' => 'required',
        //     'remittance-name' => 'required',
        //     'remittance-number' => 'required',
        //     'remittance-location' => 'required',
        //     'coins-wallet-address' => 'required'
        // ]);
        
        $validator = \Validator::make($request->all(), [
            'bdo-account-name-select' => 'required',
            'bpi-account-name-select' => 'required',
            'metro-account-name-select' => 'required',
            'security-account-name-select' => 'required',
        ]);

        

        if ($validator->passes()) {
            \App\SiteSetting::whereNotIn('account_key', $request->all())->update([
                'status' => 0
            ]);

            
            \App\SiteSetting::whereIn('account_key', $request->all())->update([
                'status' => 1
            ]);

            foreach(request()->all() as $key => $value) {

                // if(\App\SiteSetting::where('name', 'like', $key)->where('account_key','old')->count()) {
                //     \App\SiteSetting::where('name', 'like', $key)->where('account_key','old')
                //             ->update(['value' => $value]);
                // } else {
                //     \App\SiteSetting::create([
                //         'name' => $key,
                //         'value' => $value,
                //         'account_key' => 'old'
                //     ]);
                // }
            }
            return request()->all();
        } else
            return ['errors' => $validator->errors()];
    }

    public function addSiteAccount(Request $request)
    {
        
        $validator = \Validator::make($request->all(), [
            'accountType' => 'required',
            'accountName' => 'required',
            'accountNumber' => 'required',
        ]);

        if ($validator->passes()) {
            $accountType = $request->accountType;
            $accountName = $request->accountName;
            $accountNumber = $request->accountNumber;
            $accountAlias = $request->accountAlias;
            $accountKey = date('Ymdhi') . '_' . uniqid();
        
            $insertData = [
                "{$accountType}-account-name" => $accountName,
                "{$accountType}-account-number" => $accountNumber,
                "{$accountType}-account-alias" => $accountAlias,
            ];

            foreach($insertData as $key => $value){
                \App\SiteSetting::create([
                    'name' => $key,
                    'value' => $value,
                    'account_key' => $accountKey
                ]);
            }
            
            return [
                'success' => true,
                'accountKey' => $accountKey,
                'message' => 'Account Successfully added.'  
            ];
        }else{
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

    }

    public function getSiteAccountList()
    {
        $siteSettings =  \App\SiteSetting::whereNotIn('account_key', ['old'])->get()->groupBy('account_key');

        return ['accounts' => $siteSettings];
    }

    public function updateSiteAccount(Request $request)
    {
        $account_key = $request->account_key;
        $status = $request->status;

        $update = \App\SiteSetting::where('account_key', $account_key)->update([
            'status' => $status
        ]);

        return [
            'success' => $update ? true : false,
            'message' => $update ? 'Successfully updated account status.' : 'Failed to change account status.'
        ];
        
    }

    public function deleteSiteAccount(Request $request)
    {
        $account_key = $request->account_key;

        $deleted = \App\SiteSetting::where('account_key', $account_key)->delete();

        return [
            'success' => $deleted ? true : false,
            'message' => $deleted ? 'Successfully deleted account.' : 'Failed to delete account'
        ];
        
    }
    
    
    public function assignVoucherCodeToUser(Request $request)
    {
        $user = \App\User::find($request->user_id);
        $validator = \Validator::make($request->all(), [
            'voucher_percent' => 'numeric'
        ]);
        
        if ($validator->passes()) {
            $user->voucher_code = $request->voucher_code;
            $user->voucher_percent = $request->voucher_percent;
            $user->save();
            return ['success' => true];
        }else{
             return ['errors' => $validator->errors()];
        }
    }
    
    public function resetPassword(Request $request)
    {
        $user = \App\User::find($request->user_id);
        $validator = \Validator::make($request->all(), [
            'password' => 'required',
        ]);
        
        if ($validator->passes()) {
            $user->password = \Hash::make($request->password);
            $user->save();
            return ['success' => true];
        }else{
             return ['errors' => $validator->errors()];
        }
    }

    /**
     * Add user as agent
     * @param Request $request
     */
    public function changeRole(Request $request)
    {
        $user = \App\User::find($request->id);
        $user->type = $request->type;
        $user->save();
        return ['success' => true];
    }

    public function addRole(Request $request)
    {
        $user = \App\User::find($request->id);
        if(!$user->roles->contains($request->type)){
            $user->roles()->detach();
            $user->roles()->attach($request->type);   
            if($request->type == 1){
                $user->type = 'admin';
                $user->save();
            } else {
                $user->type = $request->type == 5 ? 'matchmanager' : 'user';
                $user->save();
            }
            return ['success' => true];
        }else{
            return ['success' => false];
        }
        
    }
    
    public function getAuditData() {
        if(request()->userid) {
            $data = collect();
            $user = \App\User::find(request()->userid);
            $user_id = request()->userid;
           
            switch(request()->type) {
                case 'bets':



                    $whereRaw = "date(created_at) <= '".$this->banned_from."'";
                    $data = $user->bets()->whereRaw($whereRaw)->with(['team', 'match' => function($query) {
                        $query->select('id', 'name', 'status', 'team_winner');
                    }, 'league' => function($query) {
                        $query->select('id', 'name', 'status', 'betting_status', 'league_winner');
                    }])->orderBy('updated_at', 'desc');
                


                    return \Datatables::of($data)->addColumn('potential_winnings', function (\App\Bet $bet) use($user) {
                        if($bet->match) {
                            $_matchRatio = $bet->ratio ? $bet->ratio : $bet->team->matchRatio($bet->match->id);
                            return $_matchRatio * $bet->amount;
                        } else {
                            return potentialTournamentWinningPerUserPerTeam($bet->league_id, $bet->team_id, $user->id);
                        }
                    })->make(true);
                case 'deposits':
                    $data = $user->transactions()->with('processBy', 'discrepancy')
                        ->where('type', 'deposit')->orderBy('created_at', 'desc');
                    return \Datatables::of($data)->make(true);
                case 'cashouts':
                    $data = $user->transactions()->with('processBy')
                        ->where('type', 'cashout')->orderBy('created_at', 'desc');
                    return \Datatables::of($data)->make(true);
                case 'partner_deposits':
                    $data = $user->userTransactions()->partnerUserTransactions()->with('partner')
                        ->where('type', 'deposit')->orderBy('created_at', 'desc');
                    return \Datatables::of($data)->make(true);
                case 'partner_cashouts':

                    $data = $user->userTransactions()->partnerUserTransactions()->with('partner')
                            ->where('type', 'cashout')->orderBy('created_at', 'desc');
                    
                    return \Datatables::of($data)->make(true);
                case 'rebate':
                    $data = $user->wallet()
                        ->where('type', 'rebate')->orderBy('created_at', 'desc');
                    return \Datatables::of($data)->make(true);
                case 'reward':
                    $data = $user->rewards()->with('addedBy')
                        ->orderBy('created_at', 'desc');
                    return \Datatables::of($data)->make(true);  
                case 'gift_code':
                    $data = $user->giftCodes()->orderBy('date_redeemed', 'desc');
                    return \Datatables::of($data)->make(true);                       
            }
        } else
            return \Datatables::of(collect())->make(true);
    }
    
    public function getUserAuditInfo() {

        $user = \App\User::find(request()->userid);
        $user_id = request()->userid;

        $whereRaw = "date(created_at) <= '".$this->banned_from."'";

        $profit_loss = $user->bets()
                ->whereRaw($whereRaw)
                ->sum('gains');
        $ez_deposits = $user->transactions()->with('discrepancy')
                ->where('type', 'deposit')
                ->whereYear('created_at', '>=', '2018')
                ->where('status', 'completed')
                ->get();
        $ez_cashout = $user->transactions()->where('type', 'cashout')
                ->whereYear('created_at', '>=', '2018')
                ->whereIn('status', ['completed', 'processing'])->sum('amount');
        $partner_deposit = $user->userTransactions()->partnerUserTransactions()->where('type', 'deposit')
                ->where('status', 1)->sum('amount');
        $partner_cashout = $user->userTransactions()->partnerUserTransactions()->where('type', 'cashout')
                ->whereIn('status', [0,1,-1])->sum('amount');
        $current_bets = $user->bets()
                ->whereRaw("date(bets.created_at) >= '2018-05-21'")
                ->get();
        $total_rebates = $user->wallet()->where('transfered',1)
                ->whereRaw("date(created_at) >= '2019-01-01'")
                ->sum('collected');

        $total_rewards = $user->rewards()
                ->sum('credits');

        $total_gift_codes = $user->giftCodes()->sum('amount');

        $total_betted = 0;
        $total_deposits = 0;
        foreach($current_bets as $bet) {
            if($bet->type == 'matches') {
                if(in_array($bet->match->status, ['open', 'ongoing']))
                    $total_betted += $bet->amount;
            } else {
                if($bet->league->status == 1 && $bet->league->betting_status != -1)
                    $total_betted += $bet->amount;
            }
        }
        foreach($ez_deposits as $deposit) {
            if($deposit->discrepancy) {
                $disc_amount = 0;
                foreach($deposit->discrepancy as $disc_deposit) {
                    $disc_amount += ($disc_deposit->amount ? $disc_deposit->amount : 0);
                }
                $total_deposits += ($disc_amount > 0 ? $disc_amount : $deposit->amount);
            } else
                $total_deposits += $deposit->amount;
        }

        return [
            'success' => true,
            'user_credit' => $user->credits,
            'curr_bets' => $total_betted,
            'profit_loss' => $profit_loss,
            'ez_deposit' => $total_deposits,
            'ez_cashout' => $ez_cashout,
            'partner_deposit' => $partner_deposit,
            'partner_cashout' => $partner_cashout,
            'total_rebate' => $total_rebates,
            'total_rewards' => $total_rewards,
            'total_gift_codes' => $total_gift_codes
        ];
    }

    public function removeRole(Request $request)
    {
        $user = \App\User::find($request->id);
        $user->roles()->detach($request->type);        
        if($request->type == 1){
            $user->type = 'user';
            $user->save();
        }
        return ['success' => true];
        
    }

    /**
     * Agents batch deposit
     * @param  Request $request [description]
     * @return [type]           [description]
     */ 
    public function batchDeposit(Request $request)
    {
        $rules = [
            'amount.*' => 'required',
        ];
        
        $validator = Validator::make($request->all(),$rules);
        
        if($validator->passes()) {
            return ['success' => true];
        } else {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

    }

    public function addPlayMoney(Request $request)
    {
        $user = \App\User::find($request->id);
        $user->play_money += $request->amount;
        $user->save();
    }
    
    public function getAllBadges(Request $request)
    {
        return \Datatables::of(\App\Badge::all()->load('users'))->make(true);
    }
    
    public function addEditBadges(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'credits' => 'numeric',
            'image' => $request->badge_id ? 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes()) {

            $input = $request->all();
            if ($request->image) {
                $input['image'] = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(storage_path('uploads'), $input['image']);
            }

            if($request->badge_id) {
                $badge = \App\Badge::find($request->badge_id);
                $badge->name = $request->name;
                $badge->description = $request->description;
                $badge->credits = $request->credits;
                if($request->image)
                    $badge->image = $input['image'];
                $badge->save();
            } else {
                \App\Badge::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'image' => $input['image'],
                    'credits' => $request->credits,
                ]);
            }

            return ['success' => 'done'];
        }

        return response()->json(['error' => $validator->errors()->all()]);
    }
    
    public function deleteBadges(Request $request)
    {
        $badge = \App\Badge::find($request->badge_id);
        if($badge->users->count()) {
            return ['error' => 'There are users assigned to this badge and cannot be deleted.'];
        } else
            return ['badge' => $badge->delete()];
    }
    
    public function assignBadges(Request $request)
    {
        $user = \App\User::find($request->user_id);
        $user->badges()->detach();
        if(count($request->badges)) {
            foreach($request->badges as $badge_id) {
                $badge = \App\Badge::find($badge_id);
                $user->badges()->attach($badge_id);
                if ($user->rewards->where('type', 'badge')->where('class_id', $badge->id)->count() == 0 && $badge->credits > 0) {
                    $reward = \App\Reward::create([
                                'user_id' => $user->id,
                                'type' => 'badge',
                                'class_id' => $badge->id,
                                'description' => 'Awarded Badge: ' . $badge->name . ($badge->credits ? ' with '.$badge->credits.' credits' : ''),
                                'credits' => $badge->credits,
                                'added_by' => \Auth::user()->id
                    ]);
                    $user->credits += $badge->credits;
                    $user->save();
                }
            }
        }
        return ['success' => true];
    }
    
    public function addManualRewards(Request $request) 
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'credits' => 'required|numeric',
            'reasons' => 'required'
        ]);
        if ($validator->passes()) {
            $user = \App\User::find($request->user_id);
            $reward = \App\Reward::create([
                        'user_id' => $user->id,
                        'type' => 'manual',
                        'class_id' => 0,
                        'description' => 'Manual Reward: ' . $request->reasons,
                        'credits' => $request->credits,
                        'added_by' => \Auth::user()->id
            ]);
            $user->credits += $request->credits;
            $user->save();
            return ['success' => true];
        } else
            return response()->json(['error' => $validator->errors()->all()]);
    }

    public function addUserCredits(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                    'user_id' => 'required',
                    'credits' => 'required|numeric|min:0.1',
        ]);
        if ($validator->passes()) {
            $user = \App\User::find($request->user_id);
            $user->credits = $request->credits;
            $user->save();
            return ['success' => 'done'];
        } else {
            return ['errors' => $validator->errors()];
        }
    }
    
    public function getAllRewards(Request $request)
    {
        $rewards = \App\Reward::with('user','addedBy');
        return \Datatables::of($rewards)->make(true);
    }
    
    /**
     * Reset all credits for all normal users and save top bettors of the league
     * @return \Illuminate\Http\Response
     */
    public function resetCredits()
    {
        \App\Users::query()->where('type','!=','admin')->update(['credits' => 0.00]);
        $top = new TopBettor;
        $top->league_id = 7; #sample only dont know how to get the active league 
        $top->data = leaderBoard()->get()->toJson();
        $top->save();
        return response()->json(['success' => true, 'message' => 'Credits successfully updated']);
    }
    
    /**
     * Update status of any model 
     * 
     * @param Illuminite\Http\Request $request
     * @return Response $response
     */
    public function updateStatus(Request $request) 
    {
        $validator = \Validator::make($request->all(), [
            'reasons' => 'required_with:credits',
            'credits' => 'required|numeric|min:0.1'
        ]);
        if ($validator->passes() || !$request->credits) {
            switch ($request->type) {
                case 'bug':
                    $model = ReportedBug::find($request->id);
                    $type = 'reported-bug';
                    break;
                case 'promotion':
                    $model = Promotion::find($request->id);
                    $type = 'promotion';
                    break;
                default:
                    # code...
                    break;
            }
            if($request->credits){
                $reward = \App\Reward::create([
                    'user_id' => $model->user->id,
                    'type' => $type,
                    'class_id' => 0,
                    'description' => 'Manual Reward: ' . $request->reasons,
                    'credits' => $request->credits,
                    'added_by' => \Auth::user()->id
                ]);
                $user = \App\User::find($model->user->id);
                $user->credits += $request->credits;
                $user->save();
                $model->status = 1;
            }else{
                if(!empty($model)){
                    $model->status = 2;
                }
            }
            if(!empty($model)){
                $model->proccessed_by = \Auth::user()->id;
                $model->admin_comment = $request->admin_comment;
                $model->save();
            }

            return  response()->json(['success' => true,'message' => 'Status successfully updated']);
        } else {
            return ['error' => $validator->errors()];
        }
        return  response()->json(['success' => true,'message' => 'Status successfully updated']);
    }
    
    /**
     * Reject Transaction 
     * 
     * @param Illuminite\Http\Request $request
     * @return Response $response
     */
    public function rejectTransaction(Transaction $transaction,Request $request) 
    {
        $dateNow = date('Y-m-d H:i:s');
        $validator = \Validator::make($request->all(), [
            'admin_comment' => 'required', 
        ]);
        if ($validator->passes()){
            if($transaction->status == 'processing'){
                $transaction->status = 'rejected';
                $transaction->process_by = \Auth::user()->id;
                $transaction->approved_rejected_date = $dateNow;
                $transaction->save();
                $wallet = Wallet::whereUserId($transaction->user_id)->whereMetaKey('deposit')->whereMetaValue($transaction->id)->first();
                if($wallet){
                    if($wallet->transfered){
                        $user = $transaction->user;
                        $user->credits -= $wallet->collected;
                        $user->save();
                    }else{
                        $wallet->delete();
                    }
                }
                $note = new TransactionNote;
                $note->transaction_id = $transaction->id;
                $note->user_id = \Auth::user()->id;
                $note->message = $request->admin_comment;
                $note->save();
                if($transaction->type == 'cashout'){
                    $_user = $transaction->user;
                    $_user->credits += $transaction->amount;
                    $_user->save();
                    $fee = Fee::where(['meta_value' => $transaction->id, 'meta_key' => 'cashout'])->first();
                    if(!empty($fee)){
                        $fee->collected = 0.00;
                        $fee->save();
                    }

                    $donation = Donation::where('transaction_id',$transaction->id)->first();
                    if(!empty($donation)){
                        $donation->user_id = \Auth::user()->id;
                        $donation->amount = '0.00';
                        $donation->save();
                    }

                }
                return  response()->json(['success' => true,'message' => 'Status successfully updated']);
            }else{
                return  response()->json(['success' => false,'message' => 'Transaction already rejected']);
            }
        }else{
            return ['error' => $validator->errors()];
        }
    }

    public function viewReport(\App\MatchReport $match)
    {
        // dd($match->data);
        return view('emails.match-report',compact('match'));
        // $this->dispatch(new SendMatchReport(['type' => 'settled', 'match_id' => $match->id]));
    }

    
    /**
     * Delete Transaction
     * 
     * @param App\Transaction $transaction
     * @return Response $response
     */
    public function deleteTransaction(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->delete();
        return ['success' => true];
        
    }
    
    /**
     * Return admin dashboard reports
     * @return Response $response
     */
    public function dashboard()
    {
        return response()->json(calculateCredits());
    }

    public function adminExtraAction(Request $request)  
    {
        $dateNow = date('Y-m-d H:i:s');
        $transaction = Transaction::find($request->id)->load('discrepancy');
        $user = User::find($request->user_id);
        // $discrepancy = $request->discrepancy_id ? TransactionDiscrepancy::find($request->discrepancy_id) : new TransactionDiscrepancy;
        $discrepancy = new TransactionDiscrepancy;
        if ($transaction->discrepancy->isEmpty()) {
            $last_amount = $transaction->amount;
        }else{
            if ($transaction->discrepancy->last()->amount) {
                $last_amount = $transaction->discrepancy->last()->amount;
            }else{
                $last_amount = $transaction->amount;
            }
        }
        if ($transaction->type == 'deposit') {
            $rules = [
                'message' => 'required',
            ];
        }else{
            $rules = [
                'message' => 'required',
                'photo' => 'required',
            ];
        }

        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) {
            if ($request->amount && $transaction->type == 'deposit') {
                if ($request->amount > $last_amount) {
                    $add_to_credits = $request->amount - $last_amount;
                    $user->credits += $add_to_credits;
                    $wallet = Wallet::whereUserId($transaction->user_id)->whereMetaKey('deposit')->whereMetaValue($transaction->id)->first();
                    if($wallet){
                        if($wallet->transfered){
                            $wallet->collected += $add_to_credits * $wallet->percent;
                        }else{
                            $wallet->collected = $add_to_credits * $wallet->percent;
                        }
                        $wallet->save();
                    }
                }else{
                    $minus_to_credits = $last_amount - $request->amount;
                    $user->credits -= $minus_to_credits;
                    $wallet = Wallet::whereUserId($transaction->user_id)->whereMetaKey('deposit')->whereMetaValue($transaction->id)->first();
                    
                    if($last_amount != $request->amount){
                        if($wallet){
                            if($wallet->transfered){
                                $wallet->collected -= $minus_to_credits * $wallet->percent;
                            }else{
                                $wallet->collected = $minus_to_credits * $wallet->percent;
                            }
                            $wallet->save();
                        }
                    }
                }
                $discrepancy->amount = $request->amount;
                if($transaction->commission){
                    $commission = Commission::find($transaction->commission->id);
                    $commission->amount = $request->amount * ($user->voucher_percent/100);
                    $commission->save();
                }
            }else{
                // $user->credits -= $transaction->amount;
                $donation = new Donation;
                $donation->transaction_id = $transaction->id;
                $donation->user_id = $transaction->user_id;
                // $donation->amount = $request->waive_fee ? 0.00 : $transaction->amount*0.05;
                $donation->amount = $request->waive_fee ? 0.00 : $transaction->amount * env('CASHOUT_FEE');
                $donation->save();
                // Fee
                $fee = new Fee;
                $fee->meta_key = 'cashout';
                $fee->meta_value = $transaction->id;
                // $fee->percent = '0.05';
                // $fee->collected = $request->waive_fee ? 0.00 : $transaction->amount*0.05;
                $fee->percent = env('CASHOUT_FEE');
                $fee->collected = $request->waive_fee ? 0.00 : $transaction->amount * env('CASHOUT_FEE');
                $fee->save();
            }
            $transaction->new_credits = $user->credits;

            if ($request->photo) {

                $name =  $transaction->code.'-'. time() . '.jpg';
                $base64 = substr($request->photo, strpos($request->photo,',')+1);

                // This saves the base64encoded destinationPath
                file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));

                $file = storage_path() . '/uploads/' . $name;
                $thumb = 'thumb_' . $name;

                $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);
                if ($transaction->type == 'deposit') {
                    $discrepancy->picture = '/uploads/' . $name;
                }else{
                    $transaction->picture = '/uploads/' . $name;
                }
            }
            $user->save();
            if ($transaction->type == 'deposit') {
                $discrepancy->transaction_id = $request->id;
                $discrepancy->user_id = \Auth::user()->id;
                $discrepancy->message = $request->message;
                $discrepancy->mop = $request->mop;
                $discrepancy->save();
            }else{
                $transaction->process_by = \Auth::user()->id;
            }

            $transaction->status = 'completed';
            $transaction->approved_rejected_date = $dateNow;
            $transaction->save();

            if($request->add_rebate && !$transaction->voucher_code){
                $wallet = new Wallet;
                $wallet->user_id = $request->user_id;
                $wallet->meta_key = 'deposit';
                $wallet->meta_value = $transaction->id;
                $wallet->type = 'rebate';
                //$wallet->percent = '0.05';
                // $wallet->collected = $transaction->amount *0.05 ;
                $wallet->percent = env('ADMIN_DEPOSIT_REBATE');
                $wallet->collected = $transaction->amount * env('ADMIN_DEPOSIT_REBATE') ;
                $wallet->save();
            }else{
                if( strtolower($transaction->voucher_code) == 'kuya-jop' || strtolower($transaction->voucher_code) == 'cbb'){
                    $wallet = new Wallet;
                    $wallet->user_id = $request->user_id;
                    $wallet->meta_key = 'deposit';
                    $wallet->meta_value = $transaction->id;
                    $wallet->type = 'rebate';
                    // $wallet->percent = '0.03';
                    // $wallet->collected = $transaction->amount*0.03;
                    $wallet->percent = env('DEPOSIT_REBATE');
                    $wallet->collected = $transaction->amount * env('DEPOSIT_REBATE');
                    $wallet->save();
                }
            }
            return ['success' => true];
        }else{
            return ['success' => false,'errors' => $validator->errors()];
        }


        return ['success' => true];
        
    }

    public function approveWDiscrepancy(Request $request)
    {
        $transaction = Transaction::find($request->id)->load('discrepancy');
        $dateNow = date('Y-m-d H:i:s');
        if($transaction->status != 'processing'){
            return ['success' => false,'errors' => ['alreadyProcessed' => 'Transaction already processed. Please refresh your page.']];
        }

        $user = User::find($request->user_id);
        $discrepancy = new TransactionDiscrepancy;
        $rules = [
            'message' => 'required',
            'photo' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) { 
            if ($request->amount) {
                $user->credits += $request->amount;
                $discrepancy->amount = $request->amount;
                if($transaction->commission){
                    $commission = Commission::find($transaction->commission->id);
                    $commission->amount = $request->amount * ($user->voucher_percent/100);
                    $commission->save();
                }
            }else{
                $user->credits += $transaction->amount;
            } 
            $transaction->new_credits = $user->credits;

            if ($request->photo) {

                $name = $transaction->code.'-'. time() . '-discrepancy.jpg';
                $base64 = substr($request->photo, strpos($request->photo,',')+1);

                // This saves the base64encoded destinationPath
                file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));

                $file = storage_path() . '/uploads/' . $name;
                $thumb = 'thumb_' . $name;

                $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);
                $discrepancy->picture = '/uploads/' . $name;
                $transaction->picture = '/uploads/' . $name;
            }
            $user->save();
            $discrepancy->transaction_id = $request->id;
            $discrepancy->user_id = \Auth::user()->id;
            $discrepancy->message = $request->message;
            $discrepancy->mop = $request->mop;
            $discrepancy->save();
            $transaction->process_by = \Auth::user()->id;
            $transaction->approved_rejected_date = $dateNow;
            $transaction->status = 'completed';
            
            $transaction->save();

            if($request->add_rebate && !$transaction->voucher_code){
                $wallet = new Wallet;
                $wallet->user_id = $request->user_id;
                $wallet->meta_key = 'deposit';
                $wallet->meta_value = $transaction->id;
                $wallet->type = 'rebate';
                // $wallet->percent = '0.05';
                // $wallet->collected = $transaction->amount*0.05;
                $wallet->percent = env('ADMIN_DEPOSIT_REBATE');
                $wallet->collected = $transaction->amount * env('ADMIN_DEPOSIT_REBATE');

                $wallet->save();
            }else{
                if( strtolower($transaction->voucher_code) == 'kuya-jop' || strtolower($transaction->voucher_code) == 'cbb'){
                    $wallet = new Wallet;
                    $wallet->user_id = $request->user_id;
                    $wallet->meta_key = 'deposit';
                    $wallet->meta_value = $transaction->id;
                    $wallet->type = 'rebate';
                    // $wallet->percent = '0.03';
                    // $wallet->collected = $transaction->amount*0.03;
                    $wallet->percent = env('DEPOSIT_REBATE');
                    $wallet->collected = $transaction->amount * env('DEPOSIT_REBATE');                    
                    $wallet->save();
                }
            }
            return ['success' => true,'trnasaction' => $transaction];
        }else{
            return ['success' => false,'errors' => $validator->errors()];
        }
    }
    
    public function setStatus(Request $request)
    {
        $transaction = Transaction::find($request->id)->load('commission.user');
        $dateNow = date('Y-m-d H:i:s');
       
        if ($request->status == 'completed') {
            if($transaction->status != 'processing'){
                return ['success' => false,'errors' => ['alreadyProcessed' => 'Transaction already processed. Please refresh your page.']];
            }

            $user = User::find($request->user_id);
            if ($request->type == 'deposit') {
                $user->credits += $transaction->amount;

                if($request->add_rebate && !$transaction->voucher_code){
                    $wallet = new Wallet;
                    $wallet->user_id = $request->user_id;
                    $wallet->meta_key = 'deposit';
                    $wallet->meta_value = $transaction->id;
                    $wallet->type = 'rebate';
                    // $wallet->percent = '0.05';
                    // $wallet->collected = $transaction->amount*0.05;
                    $wallet->percent = env('ADMIN_DEPOSIT_REBATE');
                    $wallet->collected = $transaction->amount * env('ADMIN_DEPOSIT_REBATE');

                    $wallet->save();
                }else{
                    if( strtolower($transaction->voucher_code) == 'kuya-jop' || strtolower($transaction->voucher_code) == 'cbb'){
                        $wallet = new Wallet;
                        $wallet->user_id = $request->user_id;
                        $wallet->meta_key = 'deposit';
                        $wallet->meta_value = $transaction->id;
                        $wallet->type = 'rebate';
                        // $wallet->percent = '0.03';
                        // $wallet->collected = $transaction->amount*0.03;
                        $wallet->percent = env('DEPOSIT_REBATE');
                        $wallet->collected = $transaction->amount * env('DEPOSIT_REBATE');                        
                        $wallet->save();
                    }
                }
            }else{
                $user->credits -= $transaction->amount;
            }
            $user->save();
        }
        $transaction->status = $request->status;
        $transaction->process_by = \Auth::user()->id;
        $transaction->approved_rejected_date = $dateNow;
        $transaction->new_credits = $user->credits;
//        $transaction->new_credits = $user->credits;
        $transaction->save();
        return ['success' => true, 'transaction' => $transaction];
        
    }

    
    /**
     * Get all referrals
     * @return \Datatables $referrals
     */
    public function getAllReferalsProfile(Request $request)
    {
        $referrals = Referal::verified()->with('referedUser.transactions','owner');
        return \Datatables::of($referrals)->make(true);
    }

    
    /**
     * Get all fees
     * @return \Datatables $fees
     */
    public function getAllFees()
    {
        $fees = Fee::query();
        return \Datatables::of($fees)->make(true);
    }

    /**
     * Add Item to market
     * @param Product
     * @return void
     */
    public function addMarketItem(Request $request)
    {
        $rules = array(
            'market_item_name'   => 'required',
            'market_item_price'  => 'required|numeric',
            'market_item_desc'   => 'required',
            'market_item_image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        );

        $validator = \Validator::make($request->all(), $rules);  
        if($validator->passes()) {
            $product = new Product;

            $product->product_name  = $request->market_item_name;
            $product->product_price = $request->market_item_price;
            $product->product_desc  = $request->market_item_desc;

            if ($request->market_item_image) {
                $image_file = time() . '.' . $request->market_item_image->getClientOriginalExtension();
                $request->market_item_image->move(storage_path('product'), $image_file);
                $product->product_image = $image_file;
            }
            $product->save();
            $return['success'] = false;

        }else{
            $return['errors'] = $validator->errors();
            return $return;
        }
    }

    /**
     * Get market item list
     * @param Product
     * @return \Datatables $product_list
     */
    public function getMarketItemList()
    {
        $product_list = Product::all();
        return \Datatables::of($product_list)->make(true);
    }

    /**
     * Get users sum of bets 
     * 
     * @param Illuminite\Http\Request $request
     * @return Response $response
     */
    public function getBetsAmount(Request $request)
    {
        $user = User::find($request->user_id);
    }

    /**
     * Payout commissions on user
     * 
     * @param Illuminite\Http\Request $request
     * @return Response $response
     */
    public function payoutUser(Request $request)
    {
        $rules = [
            'photo' => 'required',
        ];

        
        
        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) {
            $user = User::find($request->user_id);
            $payoutUntilDate = $request->untilDate;
            $payoutUntilDateMidnight = $payoutUntilDate . ' 23:59:59';

            if( $request->amount < 1000 ){
                return ['success' => false, 'message' => "Users's earnings must be at least &#8369; 1,000 in order to process his/her payout."];
            }
            else{
                $payout = new Payout;
                $payout->code = $this->payoutCode();
                $payout->user_id = $request->user_id;
                $payout->amount = $request->amount;
                $payout->message = $request->message;
                $payout->process_by = \Auth::user()->id;
                $payout->until_date = $payoutUntilDate;



                $thumb = "";
                if ($request->photo) {
    
                    $name = $payout->code.'-'. time() . '.jpg';
                    $base64 = substr($request->photo, strpos($request->photo,',')+1);
    
                    // This saves the base64encoded destinationPath
                    file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));
    
                    $file = storage_path() . '/uploads/' . $name;
                    $thumb = 'thumb_' . $name;
    
                    $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);
    
                    $payout->receipt = '/uploads/' . $name;
                }
                $payout->save();


                $commissions = $user->unpaid_commissions
                        ->where('commissions.status',0)
                        ->where('commissions.created_at', '<=', $payoutUntilDateMidnight)
                        ->load('transaction')
                        ->where('transaction.status','completed');

                if(!empty($commissions)){
                    $commissionIds = $commissions->pluck('id')->toArray();
                    Commission::whereIn('id', $commissionIds)->update(['status' => 1]);
                }

                $partnerCommissions = $user->unpaid_partner_commissions
                        ->where('commissions_partners.status',0)
                        ->where('commissions_partners.created_at', '<=', $payoutUntilDateMidnight) 
                        ->load('transaction')
                        ->where('transaction.status',1);

                
                if(!empty($partnerCommissions)){
                    $partnerCommissionsIds = $partnerCommissions->pluck('id')->toArray();
                    CommissionsPartner::whereIn('id', $partnerCommissionsIds)->update(['status' => 1]);
                }        

                $commissionsBets = $user->unpaid_bets_commissions
                        ->where('status',0)
                        ->where('date_settled', '<=', $payoutUntilDateMidnight);

                if(!empty($commissionsBets)){
                    $commissionsBetsIds = $commissionsBets->pluck('id')->toArray();
                    CommissionsBets::whereIn('id', $commissionsBetsIds)->update(['status' => 1]);
                }                  

                return ['success' => true, 'message' => "Payout transaction has been processed. ".$user->name." earnings has been reset to zero."];
            }
        }else{
            return ['success' => false,'errors' => $validator->errors()];
        }
    }

    public function getAllPayout(Request $request)
    {   
        return Datatables::of(Payout::with('user','process_by')->select('payouts.*'))->make(true);
    }

    private function payoutCode() 
    { 
    	do{
	    	$type = 'PO';
		    $chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ"; 
		    srand((double)microtime()*1000000); 
		    $i = 0; 
		    $pass = '' ; 

		    while ($i <= 7) { 
		        $num = rand() % 33; 
		        $tmp = substr($chars, $num, 1); 
		        $pass = $pass . $tmp; 
		        $i++; 
		    }
		    $code = $type.'-'.$pass;
    	}while (!\App\Payout::where('code',$code)->get()->isEmpty());

	    return $code; 
    }

    /**
     * Get all rebates
     * 
     * @param Illuminate\Http\Request
     * @return Datatables $promo
     */
    public function getRebates()
    {
        $rebates = Wallet::type()->with('user')->select('wallets.*');
        return \Datatables::of($rebates)->make(true);
    }

    public function updateBanStatus(Request $request){
        $user_id = $request->input('id');
        $currentlyBanned = $request->input('status') == 1;
        $user = \App\User::find($user_id);

        if(!empty($user)){
            if($currentlyBanned){
                $user->banned_until = NULL;
                $user->save();
                $user->giftCodesAttempts()->delete();
                return json_encode([
                    'success' => true,
                    'message' => 'Removed ban status of user ' . $user->name
                ]);                
            }else{
                $untilYears = Carbon::now()->addYears(10);
                $user->banned_until = $untilYears;
                $user->save();
                return json_encode([
                    'success' => true,
                    'message' => 'Successfully banned user ' .$user->name
                ]);  
            }

        }else{
            return json_encode([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    }

    public function getAffliates()
    {
        // $affliateBadgesIds = \App\Badge::where('name','AFFILIATE')->pluck('id');
        $affliateBadgesIds = \App\Badge::whereIn('name',['AFFILIATE','SUB-AFFILIATE'])->pluck('id'); //UPDATE use whereIn to get both main & sub streamer

        $affliates = \DB::table('users')
                    ->join('badge_user','users.id', '=','badge_user.user_id')
                    ->whereIn('badge_user.badge_id',$affliateBadgesIds)
                    ->where('users.voucher_code', '!=', NULL)
                    ->where('users.voucher_percent', '>',0)
                    ->get();

        return json_encode($affliates);
    }

    public function updateAffliates(Request $request)
    {
        $partner_id = $request->input('partner_id');
        $affliates = $request->input('affliates');

        //we delete first then we update after
        \App\PartnersJoint::where('partner_id', $partner_id)->delete();

        $insertData = [];
        if(!empty($affliates)){
            foreach($affliates as $affliate){
                $insertData[] = [
                    'partner_id' => $partner_id,
                    'streamer_user_id' => $affliate
                ];
            }
        }


        if(!empty($insertData)){
            \App\PartnersJoint::insert($insertData);
        }
        
        return json_encode([
            'success' => true,
            'message' => 'Partner affliates updated'
        ]);
    }

    public function getSubAgents()
    {
        $badgeIds = \App\Badge::where('name','PARENT PARTNER')->pluck('id');

        $userAgents = \DB::table('badge_user')
                    ->whereIn('badge_id',$badgeIds)
                    ->get();

        $alreadySubs = \App\PartnerSubAgents::all()->pluck('sub_partner_id');

        $partners = \App\Partner::where('verified', 1)
                                    ->whereNotIn('user_id', $userAgents->pluck('user_id'))
                                    // ->whereNotIn('id', $alreadySubs)
                                    ->get();

        return json_encode([
            'partners' => $partners,
            'alreadySubs' => $alreadySubs
        ]);        
    }

    public function updateSubAgents(Request $request)
    {
        $partner_id = $request->input('partner_id');
        $agents = $request->input('agents');

        //we delete first then we update after
        \App\PartnerSubAgents::where('partner_id', $partner_id)->delete();

        $insertData = [];
        if(!empty($agents)){
            foreach($agents as $agent){
                $insertData[] = [
                    'partner_id' => $partner_id,
                    'sub_partner_id' => $agent
                ];
            }
        }


        if(!empty($insertData)){
            \App\PartnerSubAgents::insert($insertData);
        }
        
        return json_encode([
            'success' => true,
            'message' => 'Partner sub-agents updated'
        ]);
    }

    public function getPartnerSubUsers()
    {
        $badgIds = \App\Badge::where('name','PARTNER SUB-USER')->pluck('id');

        $subUsers = \DB::table('users')
                    ->join('badge_user','users.id', '=','badge_user.user_id')
                    ->whereIn('badge_user.badge_id',$badgIds)
                    ->get();
        
        return json_encode($subUsers);
    }

    public function updatePartnerSubUsers(Request $request)
    {
        $partner_id = $request->input('partner_id');
        $subUsers = $request->input('sub_users');

        //we delete first then we update after
        \App\PartnerSubUsers::where('partner_id', $partner_id)->delete();

        $insertData = [];
        if(!empty($subUsers)){
            foreach($subUsers as $subUser){
                $insertData[] = [
                    'partner_id' => $partner_id,
                    'user_id' => $subUser
                ];
            }
        }


        if(!empty($insertData)){
            \App\PartnerSubUsers::insert($insertData);
        }
        
        return json_encode([
            'success' => true,
            'message' => 'Partner sub-users updated'
        ]);
    }
    
    public function markAsVerified(Request $request)
    {
        $user_id = $request->user_id;
        $verified_by_user_id = \Auth::id();
        if(!empty($user_id)){

            $verified = \App\VerifiedBy::firstOrCreate([
                'user_id' => $user_id,
                'verified_type' => '2ez',
                'verified_by' => 0,
                'verified_by_user_id' =>  $verified_by_user_id
            ]);

            return [
                'success' => true,
                'message' => 'Successfully verified.'
            ];
        }else{
            return [
                'success' => false,
                'message' => 'User not found.'
            ];
        }
    
    }

    public function getUserPartnersVerified(Request $request)
    {
        $user_id = $request->user_id;
        if(!empty($user_id)){

            $partners = \App\VerifiedBy::where([
                'user_id' => $user_id,
                'verified_type' => 'partner',
            ])->with('partner')->get();

            return [
                'success' => true,
                'partners' => $partners
            ];

        }else{
            return [
                'success' => false,
                'message' => 'User not found.'
            ];
        }        
    }

    /**
     * Get earnings per match
     * 
     * @param Illuminate\Http\Request
     * @return Datatables $promo
     */
    public function getEarnings()
    {
        $matches = \App\Match::leftJoin('match_reports', 'matches.id', '=', 'match_reports.id')
        ->select(
            'matches.id',
            'matches.name',
            'matches.schedule',
            'matches.created_at',
            'matches.status',
            'team_winner',
            'round_off_earnings',
            'matches.league_id'
        )
        ->with('teamA')
        ->with('teamB')
        ->with('league')
        ->with('teamwinner');

        return \Datatables::of($matches)->make(true);
    }
 
    public function getBetLogs(Request $request, $user_id = 0) {
        $start_date = strtotime($request->start_date) ? date("Y-m-d", strtotime($request->start_date)) : date("Y-m-d", strtotime(date("Y-m-d") . ' -30 day'))    ;
        $end_date   = strtotime($request->end_date) ? date("Y-m-d", strtotime($request->end_date)) : "";

        if(is_numeric($user_id) && $user_id > 0) {
            $user = User::where('id', $user_id)->first();
            $logs = \App\Log::where('user_id', $user_id)
            ->when($start_date != 0, function ($q) use ($start_date) {
                $q->where('created_at', '>=', $start_date);
            })
            ->when($end_date != 0, function ($q) use ($end_date) {
                $q->where('created_at', '<=', date("Y-m-d", strtotime($end_date . ' +1 day')));
            })
            ->orderBy('id', 'created_at')
            ->get();
        } else {
            abort(404);
        }

        return view('user.userCreditLogs', compact('logs', 'user', 'start_date', 'end_date'));
    }

    public function getAllBets(Request $request)
    {
        $bets = \App\Bet::with('user','team','match.teamwinner','league.champion');
        return \Datatables::of($bets)->make(true);
    }
}


