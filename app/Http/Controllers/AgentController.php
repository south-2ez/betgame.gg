<?php

namespace App\Http\Controllers;

use Image;
use Validator;
use Illuminate\Http\Request;
use App\Partner;
use App\PartnerTransaction;
use App\PartnerPayout;
use App\PartnerDiscrepancy;
use App\PartnerDonation;
use App\PartnerProfile;
use App\User;
use App\Fee;
use App\Transaction;
use App\TransactionDiscrepancy;
use App\TransactionNote;
use App\CommissionsPartner;
use App\Province;
use App\Wallet;
use Carbon\Carbon;
use Cache;

class AgentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     
        
        $this->middleware('auth', ['except' => 
                                            [
                                                
                                            ]
                                    ]
                        );
    }

    public function agentPage()
    {
        $user = !\Auth::guest() ? \Auth::user() : null;
        $dashboardAccessKey = 'hasPartnerDashboardAccess_' . getUserCacheKey();

        $hasPartnerDashboardAccess = $user ? Cache::remember( $dashboardAccessKey, 120 , function () use ($user){

            return hasPartnerDashboardAccess($user);
        
        }) : false;

        if( empty($user) || !$hasPartnerDashboardAccess){
            abort(404);
        }
        
        $beta_badge = \App\Badge::where('name','like','betatester')->first();
       
        $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();

        $partnership = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

            return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
        
        });

       // $partnership = isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
      
        
        $provincesKey = 'provinces_' . getUserCacheKey();

        $provinces = Cache::remember( $provincesKey, 120 , function (){

            return Province::all();
        
        });

        if($partnership){
            if( $partnership->verified > 0 && $partnership->active > 0 ){
                
                $parentPartnerKey = 'parentPartner_' . getUserCacheKey();
                $parentPartner = Cache::remember( $parentPartnerKey, 120 , function () use ($partnership){

                    return $partnership->parentPartner;
                
                });

                return view('agent.management', compact('beta_badge', 'partnership', 'provinces', 'parentPartner', 'user'));
            }else{
                return redirect('/');
            }
        }

    }

    private function activePartner($partner_id) 
    {
        $partner = Partner::find($partner_id);
        if($partner->active > 0)
        {
            $active = true;
        }
        else
        {
            $active = false;
        }
        return $active;
    }

    public function partnerRemainingCredits()
    {
        $user = \Auth::user();
        $partner = isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
        if(!empty($partner)){
            $partner_credits = $partner->partner_credits ? $partner->partner_credits : "0.00";
            $earnings = $partner->partner_earnings ? $partner->partner_earnings : "0.00";
            $credits = $user->credits;
            return response()->json(['credits' => $partner_credits, "earnings" => $earnings, "current" => $credits]);
        }else{
            abort(404);
        }

    }

    public function remainingCredits()
    {
        $credits =\Auth::user()->credits;
        return response()->json(['credits' => $credits]);
    }

    public function partnerProviders(Request $request)
    {
        $partners = Partner::all();
        $provinces = Province::all();
        return response()->json(['partners' => $partners, "provinces" => $provinces]);
    }

    public function partnerPayouts(Request $request)
    {

        if(\Auth::user()->isAdmin()){
            $transactions = PartnerPayout::with('processBy', 'partner','partner.userOwner');

            return \Datatables::of($transactions)
                                ->addColumn('partner.partner_name', function($data){
                                    return  $data->partner ? $data->partner->partner_name : null;
                                })
                                ->addColumn('process_by.name', function($data){
                                    return  $data->processBy ? $data->processBy->name : null;
                                }) 
                                ->addColumn('partner.user_owner.name', function($data){
                                    return  $data->partner && $data->partner->userOwner ?$data->partner->userOwner->name : null;
                                })                                 
                                ->filter(function ($query) use ($request) {
                                                        
                                    if(!empty($request->search['value'])){
                                        $searchValue = $request->search['value'];

                                        $query->orWhereHas('partner', function($q) use($searchValue){
                                            $q->where('partner_name','like', "%{$searchValue}%");
                                            $q->orWhereHas('userOwner', function($q2) use ($searchValue){
                                                $q2->where('name','like', "%{$searchValue}%");
                                            });
                                        });

                                        $query->orWhereHas('processBy', function($q) use($searchValue){
                                            $q->where('name','like', "%{$searchValue}%");
                                        });

                                        switch( strtolower($searchValue)){
                                            case 'approved':
                                                $query->orWhere('status',1);
                                                break;
                                            case 'rejected': 
                                                $query->orWhere('status',2);
                                                break;
                                            case 'incomplete': 
                                                $query->orWhere('status',0);
                                                break;
                                        }
                                    }

                                }, true) 
                                ->make(true);
        }else{
            abort(404);
        }
    }

    public function payoutDatatable(Request $request)
    {
        // $transactions = PartnerPayout::all()->where('partner_id', \Auth::user()->userPartner->id)->load('processBy', 'partner.userOwner');
        // return \Datatables::of($transactions)->make(true);

        if(!empty( \Auth::user()->userPartner )){
            $where = [
                ['partner_id',  \Auth::user()->userPartner->id],
            ];        

            $transactions = PartnerPayout::where($where);
            
            return \Datatables::of($transactions)
                                ->addColumn('process_by.name', function($data){
                                    return  $data->processBy ? $data->processBy->name : null;
                                })
                                // ->addColumn('partner.partner_name', function($data){
                                //     return  $data->partner ? $data->partner->partner_name : null;
                                // })
                                // ->addColumn('partner.province', function($data){
                                //     return  $data->partner ? $data->partner->province : null;
                                // })                            
                                ->rawColumns(['data'])->make(true);
        }else{
            abort(404);
        }

    }

    public function partnerUserTransactions(Request $request)
    {
        $user = \Auth::user();

        if($request->user_id && $user->id == $request->user_id){
      
            $where = [
                ['partner_transactions.user_id',  $request->user_id],
                ['partner_transactions.type', $request->type],
                ['partner_transactions.trade_type', $request->trade_type]
            ];        
                
            $transactions = \DB::table('partner_transactions')
                                ->join('users', 'users.id', '=', 'partner_transactions.user_id')
                                ->join('partners', 'partners.id', '=', 'partner_transactions.partner_id')
                                ->select('partner_transactions.*', 'users.name as partner_transactions.name', 'partners.partner_name')
                                ->where($where)
                                ->orderBy('partner_transactions.id','desc')
                                ->get();

            return \Datatables::of($transactions)->make(true);                       

        }else{

            // $partner = isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;

            $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();
            $partner = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

                return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
            
            });
                        
            if(!empty($partner)){
                $partner_id = $partner->id;

                $where = [
                    ['partner_id', $partner_id],
                    ['type', $request->type],
                    ['trade_type', $request->trade_type]
                ];

                $request->partner_id = $partner_id;

                $transactions = PartnerTransaction::where($where)->with('user','processBy','partner');
                            

                return \Datatables::of($transactions)
                                    ->addColumn('process_by.name', function($data){
                                        return  $data->processBy ? $data->processBy->name : null;
                                    })
                                    ->addColumn('verified_by', function($data){
                                        return  $data->user->verified_by ? $data->user->verified_by->toArray() : null;
                                    })
                                    ->make(true);

            }else{
                abort(404);
            }


        }
    }

    public function partnerSubAgentTransactions(Request $request)
    {

        $partner = \Auth::user()->userPartner;

        if(!empty($partner)){
            $partner_id = $partner->id;
            $where = [
                ['partner_transactions.trade_type', $request->trade_type],
                ['partner_transactions.main_partner_id', $partner_id]
            ];        
                
            $transactions = \DB::table('partner_transactions')
                                ->join('users', 'users.id', '=', 'partner_transactions.user_id')
                                ->join('partners', 'partners.id', '=', 'partner_transactions.partner_id')
                                ->select('partner_transactions.*', 'users.name as partner_transactions.name', 'partners.partner_name')
                                ->where($where)
                                ->orderBy('partner_transactions.id','desc')
                                ->get();

            return \Datatables::of($transactions)->make(true);

        }else{
            abort(404);
        }


        
    }

    public function partnerAdminTransactions(Request $request)
    {
        $user =\Auth::user();
        $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();
        $partner = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

                return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
            
        });

                // $partner_id = $partner->id;

                // $where = [
                //     ['partner_id', $partner_id],
                //     ['type', $request->type],
                //     ['trade_type', $request->trade_type]
                // ];

                // $request->partner_id = $partner_id;

                // $transactions = PartnerTransaction::where($where)->with('user','processBy','partner');
                            

                // return \Datatables::of($transactions)
                //                     ->addColumn('process_by.name', function($data){
                //                         return  $data->processBy ? $data->processBy->name : null;
                //                     })
                //                     ->addColumn('verified_by', function($data){
                //                         return  $data->user->verified_by ? $data->user->verified_by->toArray() : null;
                //                     })
                //                     ->make(true);        

        if(!empty($partner) && $partner->id == $request->partner_id){

            // $whereRaw = "( partner_transactions.partner_id = '{$request->partner_id}' OR partner_transactions.main_partner_id = '{$request->partner_id}' )";

            // $transactions = \DB::table('partner_transactions')
            //                     ->join('users', 'users.id', '=', 'partner_transactions.user_id')
            //                     ->join('partners', 'partners.id', '=', 'partner_transactions.partner_id')
            //                     ->leftJoin('partners as parentPartner', 'parentPartner.id', '=', 'partner_transactions.main_partner_id')
            //                     ->select('partner_transactions.*', 'users.name as partner_transactions.name', 'partners.partner_name', 'parentPartner.partner_name as parent_partner_name')
            //                     ->whereRaw($whereRaw)
            //                     ->where('partner_transactions.status',1)
            //                     // ->whereIn('partner_transactions.trade_type', ['partner-admin', 'partner-sub'])
            //                     ->orderBy('partner_transactions.id','desc')
            //                     ->get();

            // return \Datatables::of($transactions)->make(true);  

            /** new */

                $partner_id = $partner->id;



                $request->partner_id = $partner_id;

                $whereRaw = "( partner_transactions.partner_id = '{$request->partner_id}' OR ( partner_transactions.trade_type = 'partner-sub' AND partner_transactions.main_partner_id = '{$request->partner_id}') )";

                // $transactions = PartnerTransaction::where($where)->with('user','processBy','partner');
                $transactions = PartnerTransaction::where('partner_transactions.status',1) ->whereRaw($whereRaw)->with('user','processBy','partner');

                return \Datatables::of($transactions)
                                    ->addColumn('process_by.name', function($data){
                                        return  $data->processBy ? $data->processBy->name : null;
                                    })
                                    ->addColumn('verified_by', function($data){
                                        return  $data->user->verified_by ? $data->user->verified_by->toArray() : null;
                                    })
                                    ->make(true);  

            /** end new */





            
        }else{
            abort(404);
        }


    }

    public function partnerBuySellAdminTransactions(Request $request)
    {
        // $partner = \Auth::user()->userPartner;
        $user =\Auth::user();
        $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();
        $partner = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

                return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
            
        });

        if(!empty($partner) && $partner->id == $request->partner_id){
            $whereRaw = "( partner_transactions.partner_id = '{$request->partner_id}' OR partner_transactions.main_partner_id = '{$request->partner_id}' )";

            $transactions = \DB::table('partner_transactions')
                                ->join('users', 'users.id', '=', 'partner_transactions.user_id')
                                ->join('partners', 'partners.id', '=', 'partner_transactions.partner_id')
                                ->leftJoin('partners as parentPartner', 'parentPartner.id', '=', 'partner_transactions.main_partner_id')
                                ->select('partner_transactions.*', 'users.name as partner_transactions.name', 'partners.partner_name', 'parentPartner.partner_name as parent_partner_name')
                                ->whereRaw($whereRaw)
                                ->whereIn('partner_transactions.trade_type', ['partner-admin', 'partner-sub'])
                                ->orderBy('partner_transactions.id','desc')
                                ->get();

            return \Datatables::of($transactions)->make(true);       
        }else{
            abort(404);
        }

    }

    public function partnerUserTransactionsDashboard(Request $request)
    {
        if(\Auth::user()->isAdmin()){
            // $transactions = PartnerTransaction::all()->where('type', $request->type)->where('trade_type', $request->trade_type)->load('partnerTransactions', 'partner', 'processBy', 'partner.province', 'partner.userOwner');
            $where = [
                ['type', $request->type],
                ['trade_type', $request->trade_type]
            ];

            $transactions = PartnerTransaction::where($where);
        
            return \Datatables::of($transactions)
                                ->addColumn('partner_transactions.name', function($data){
                                    return  $data->partnerTransactions ? $data->partnerTransactions->name : null;
                                })
                                ->addColumn('partner.partner_name', function($data){
                                    return  $data->partner ? $data->partner->partner_name : null;
                                })
                                ->addColumn('process_by.name', function($data){
                                    return  $data->processBy ? $data->processBy->name : null;
                                })   
                                ->addColumn('donation', function($data){
                                    return  $data->donation ? $data->donation->amount : '0.00';
                                }) 
                                ->filter(function ($query) use ($request) {
                                    
                                    if(!empty($request->search['value'])){
                                        $searchValue = $request->search['value'];

                                        $query->orWhereHas('partnerTransactions', function($q) use($searchValue){
                                            $q->where('name','like', "%{$searchValue}%");
                                        });

                                        $query->orWhereHas('partner', function($q) use($searchValue){
                                            $q->where('partner_name','like', "%{$searchValue}%");
                                        });

                                        $query->orWhereHas('processBy', function($q) use($searchValue){
                                            $q->where('name','like', "%{$searchValue}%");
                                        });

                                        switch( strtolower($searchValue)){
                                            case 'approved':
                                                $query->orWhere('status',1);
                                                break;
                                            case 'rejected': 
                                                $query->orWhere('status',2);
                                                break;
                                            case 'incomplete': 
                                                $query->orWhere('status',0);
                                                break;
                                        }
                                    }

                                }, true)                         
                                ->rawColumns(['data'])->make(true);
        }else{
            abort(404);
        }

    }

    public function partnerAdminTransactionsDashboard(Request $request)
    {
        if(\Auth::user()->isAdmin()){
            $transactions = PartnerTransaction::where('trade_type', $request->trade_type)
                                ->with(
                                    [
                                        'partnerTransactions',
                                        'partner', 
                                        'processBy', 
                                        'discrepancy.processBy'
                                    ]
                                    
                                );

            return \Datatables::of($transactions)
                            ->addColumn('partner_transactions.name', function($data){
                                    return  $data->partnerTransactions ? $data->partnerTransactions->name : null;
                            })
                            ->addColumn('process_by.name', function($data){
                                return  $data->processBy ? $data->processBy->name : null;
                            })
                            ->filter(function ($query) use ($request) {
                                                        
                                if(!empty($request->search['value'])){
                                    $searchValue = $request->search['value'];
                                    
                                    // $query->orWhereHas('partner', function($q) use($searchValue){
                                    //     $q->where('partner_name','like', "%{$searchValue}%");
                                    //     $q->orWhereHas('userOwner', function($q2) use ($searchValue){
                                    //         $q2->where('name','like', "%{$searchValue}%");
                                    //     });
                                    // });

                                    $query->orWhereHas('processBy', function($q) use($searchValue){
                                        $q->where('name','like', "%{$searchValue}%");
                                    });

                                    switch( strtolower($searchValue)){
                                        case 'approved':
                                            $query->orWhere('status',1);
                                            break;
                                        case 'rejected': 
                                            $query->orWhere('status',2);
                                            break;
                                        case 'incomplete': 
                                            $query->orWhere('status',0);
                                            break;
                                        case 'buy credits': 
                                            $query->orWhere('type','deposit');
                                            break; 
                                        case 'sell credits': 
                                            $query->orWhere('type','cashout');
                                            break;                                         
                                    }
                                }

                            }, true) 
                            ->rawColumns(['data'])->make(true);
        }else{
            abort(404);
        }


    
    }

    public function allPartners(Request $request)
    {
        if(\Auth::user()->isAdmin()){
            $partners = Partner::all()
                        ->makeVisible(
                            [
                                'partner_credits',
                                'partner_earnings',
                            ]
                        )
                        ->where('verified', $request->verified)->load('userOwner', 'province', 'processBy','affliates.user','subUsers.user','subAgents');
        }else{
            $partners = Partner::all()->where('verified', $request->verified)->load('userOwner', 'province', 'processBy','affliates.user');
            foreach($partners as $partner){
                
                $partner->userOwner->makeHidden('credits');
                $partner->processBy->makeHidden('credits');
                if(!empty($partner->affliates)){
                    foreach($partner->affliates as $affliate){
                        $affliate->user->makeHidden('credits');
                    }
                }
            }
        }
        
        return \Datatables::of($partners)->make(true);
    }

    public function allPartnersForTransact(Request $request)
    {
        $partners = Partner::select('id', 'partner_name', 'province_id', 'address', 'show_in_site')->where( ['active' => 1, 'show_in_site' => 1 ])->get()->load('province', 'profile', 'affliates.user');
        foreach($partners as $partner){
            
            if(!empty($partner->affliates)){
                foreach($partner->affliates as $affliate){
                    $affliate->user->makeHidden('credits');
                }
            }
        }
        return ['partners' => $partners];
    }

    public function partnerStatus(Request $request)
    {
        if(\Auth::user()->isAdmin()){
            $partner = Partner::find($request->id);
            if($request->type == 'verify'){
                $partner->verified = 1;
                $partner->active = 1;
            }
            else if($request->type == 'deactivate'){
                $partner->active = 0;
            }
            else{
                $partner->active = 1;
            }
            $partner->process_by = \Auth::user()->id;
            $partner->save();

            return response()->json(['success' => true]);
        }else{
            abort(404);
        }

    }

    public function partnerShowHide(Request $request)
    {
        if(\Auth::user()->isAdmin()){
            $partner = Partner::find($request->id);
            $partner->show_in_site = $request->type == 'show' ? 1 : 0;
            $partner->process_by = \Auth::user()->id;
            $partner->save();

            return response()->json(['success' => true]);                
        }else{
            abort(404);
        }
    
    }

    public function approvedTransactions(Request $request)
    {
        $dateNow = date('Y-m-d H:i:s');

        $user = \Auth::user();

        $partner = Partner::find($request->partner_id);
        $active = $this->activePartner($request->partner_id);
        $transaction = PartnerTransaction::find($request->id);

        if(!$user->isAdmin() && $transaction->trade_type == 'partner-sub'){

        
            $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();
            $parentPartner = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

                return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
            
            });

            if($transaction->parentPartner && $parentPartner->id == $transaction->main_partner_id){
                //should continue
            }else{
                return [
                        'success' => false, 
                        'message' => 'ERROR: Sub-agent not found.'
                    ];
            }

        }


        if($transaction && $transaction->status == 0){
            if(!$active){

                return [
                        'success' => false, 
                        'message' => 'Transactions with partner <b style="color: red;">'.$partner->partner_name.'</b> are currently disabled due to deactivation.' 
                    ];

            }else{

                $transaction->status = $request->status;

                if($transaction->trade_type == 'partner-sub'){
                    
                    if($transaction->parentPartner){

                        if( ($transaction->parentPartner->partner_credits < $transaction->amount) && $transaction->type == 'deposit' ){
                            return [
                                'success' => false,
                                'message' => 'Your have insufficient partner wallet funds. Please contact 2ez.bet admins to purchase credits.'
                            ];
                        }else{ //we deduct
                            if ($request->type == 'deposit') {

                            }

                            switch($request->type){
                                case 'deposit':

                                    $transaction->parentPartner->decrement('partner_credits', $transaction->amount);
                                    break;

                                case 'cashout':

                                    $transaction->parentPartner->increment('partner_credits', $transaction->amount);
                                    break;
                            }

                        }

                    }else{
                        return [
                            'success' => false, 
                            'message' => 'Main / Parent Partner not found. Please contact admin.' 
                        ];
                    }

                }

                if ($request->status == 1) {
                    if ($request->type == 'deposit') {
                        // $partner->partner_credits += $transaction->amount;
                        // $partner->save();
                        $partner->increment('partner_credits', $transaction->amount);
                    }

                    $transaction->process_by = \Auth::user()->id;
                    $transaction->new_credits = $partner->partner_credits;
                    $transaction->approved_rejected_date = $dateNow;
                    $transaction->save();

                    return [
                        'success' => true, 
                        'message' => $transaction->code.' has been approved.', 
                        'partner' => $partner
                    ];
                }
            }
        }
        else{
            return ['success' => false, 'message' => 'Transaction code '.$transaction->code.' has already been processed.'];
        }
    }

    public function declinedTransactions(Request $request)
    {
        $dateNow = date('Y-m-d H:i:s');
        
        $user = \Auth::user();

        // $partner = Partner::find($request->partner_id);
        // $active = $this->activePartner($request->partner_id);
        $transaction = PartnerTransaction::find($request->id);
        $partner = Partner::find($transaction->partner_id);

        if(!$user->isAdmin() && $transaction->trade_type == 'partner-sub'){

        
            $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();
            $parentPartner = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

                return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
            
            });

            if($transaction->parentPartner && $parentPartner->id == $transaction->main_partner_id){
                //should continue
            }else{
                return [
                        'success' => false, 
                        'message' => 'ERROR: Sub-agent not found.'
                    ];
            }

        }

        if($transaction && $transaction->status == 0){
            $transaction->status = $request->status;
            if ($request->status == 2) {
                if ($request->type == 'cashout') {
                    // $partner->partner_credits += $transaction->amount;
                    // $partner->save();
                    $partner->increment('partner_credits', $transaction->amount);
                }

                $transaction->process_by = \Auth::user()->id;
                $transaction->partner_comment = $request->partner_comment;
                $transaction->approved_rejected_date = $dateNow;
                $transaction->save();
                return ['success' => true, 'message' => $transaction->code.' has been rejected.', 'partner'=>$partner];
            }
        }
        else{
            return ['success' => false, 'message' => 'Transaction code '.$transaction->code.' has already been processed.'];
        }
    }

    public function acceptDeclineUserDeposits(Request $request)
    {
        /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $cacheKey = 'processingPartnerTransaction_' . $request->id;

        if ( !Cache::store('redis_svr03')->has($cacheKey)) {
            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);
        }else{

            return [
                'success' => false, 
                'message' => 'Unfinish processing of transaction ID: ' . $request->id . '. Please try again.'
            ];              
        }


        $dateNow = date('Y-m-d H:i:s');

        if($request->approved == 'approved'){   
            
            $user = \Auth::user();
            $partner = isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
            $transactions = PartnerTransaction::where('id', $request->id)->where('partner_id', $partner->id)->first();

            $buyer = User::find($request->buyer_id);
            if($transactions && $transactions->status == 0){
                if($partner->partner_credits >= $transactions->amount){
                    $transactions->status = 1;
                    $transactions->remaining_credits = $partner->partner_credits;
                    $transactions->new_credits = ($partner->partner_credits - $transactions->amount);
                    $transactions->process_by = \Auth::user()->id;
                    $transactions->approved_rejected_date = $dateNow;
                    $transactions->save();
    

                    if($buyer->credits != NULL){
                        $buyer->increment('credits', $transactions->amount);
                    }else{
                        $buyer->credits += $transactions->amount;
                        $buyer->save();
                    }
                

                    // $partner->partner_credits = $transactions->new_credits;
                    // $partner->partner_earnings += $transactions->partner_earnings;
                    // $partner->save();
                    $partner->decrement('partner_credits', $transactions->amount);
                    $partner->increment('partner_earnings', $transactions->partner_earnings);

                    $parentPartner = $partner->parentPartner;

                    if(!empty($parentPartner)){
                        $parentPartner->partner->increment('partner_earnings',  $transactions->main_partner_earnings);
                    }
                    
                    Cache::store('redis_svr03')->forget($cacheKey);
                    return ['success' => true, 'message' => 'Transaction has been confirmed. Credits have been successfully transferred to '.$buyer->name.'.'];
                }
                else if($partner->partner_credits < $transactions->amount){

                    Cache::store('redis_svr03')->forget($cacheKey);
                    return ['success' => false, 'message' => 'Your have insufficient partner wallet funds. Please contact 2ez.bet admins to purchase credits.'];
                }
            }
            else{
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => false, 'message' => 'Transaction already processed or does not exist.'];
            }
        }
        else if($request->approved == 'declined'){
            $transactions = PartnerTransaction::find($request->id);
            if($transactions->status == 0){
                $transactions->status = 2;
                $transactions->partner_comment = $request->partner_comment;
                $transactions->process_by = \Auth::user()->id;
                $transactions->approved_rejected_date = $dateNow;
                $transactions->save();

                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => true, 'message' => 'Transaction has been rejected.'];
            }
            else{
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => false, 'message' => 'Transaction already processed or does not exist.'];
            }
        }
    }

    public function acceptDeclineUserCashouts(Request $request)
    {
        /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */

        $cacheKey = 'processingPartnerTransaction_' . $request->id;

        if ( !Cache::store('redis_svr03')->has($cacheKey)) {
            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);
        }else{

            return [
                'success' => false, 
                'message' => 'Unfinish processing of transaction ID: ' . $request->id . '. Please try again.'
            ];              
        }

        $dateNow = date('Y-m-d H:i:s');

        if($request->approved == 'approved'){
            
            $user = \Auth::user();
            $partner = isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
            $transactions = PartnerTransaction::where('id', $request->id)->where('partner_id', $partner->id)->first();
            $buyer = User::find($request->buyer_id);


            /**
             * Set fee percentages & multiplier
             */
            $cashoutFee = env('CASHOUT_FEE',0.05); 
            $isTimPartnerAgent = $partner->id == env('TIMS_AGENT_PARTNER_ID');
            //check if its TIM'S agent/partner; if true, use different number
            $partnerCashoutCommission = $isTimPartnerAgent ? env('TIMS_PARTNER_CASHOUT_COMMISSIONS',0.025) :  env('PARTNER_CASHOUT_COMMISSIONS',0.03);
            
            /**
             * End Set fee percentages & multiplier
            */


            if($transactions && $transactions->status == 0){
                $transactions->remaining_credits = $partner->partner_credits;
        
                $transactions->partner_earnings = (($transactions->amount - ($transactions->amount * $cashoutFee )) * $partnerCashoutCommission );
                $transactions->status = 1;
                $transactions->process_by = \Auth::user()->id;
                $transactions->approved_rejected_date = $dateNow;

                //increment partner credits/wallets - less cashout fee
                $partner->increment('partner_credits', ( $transactions->amount - ($transactions->amount * $cashoutFee ) ) );
                $partner->increment('partner_earnings', $transactions->partner_earnings);                

                if(!$isTimPartnerAgent){
                    if(!empty($parentPartner)){
                        $parentPartner->partner->increment('partner_earnings',  $transactions->main_partner_earnings);
                    }     
                }
           

                $transactions->new_credits = $partner->partner_credits;
                $transactions->save();
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => true, 'message' => 'Request cashout transaction has been settled.']; 
            }
            else{
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => false, 'message' => 'This transaction has already been confirmed or does not exist.'];
            }        
        }
        else if($request->approved == 'verified'){
            $transactions = PartnerTransaction::find($request->id);
            if($transactions->status == -1){
                $transactions->status = 0;
                $transactions->verified_by = \Auth::user()->id;
                $transactions->save();

                Cache::store('redis_svr03')->forget($cacheKey);
                return response()->json(['success' => true, 'message'=>'Cashout code '.$transactions->code.' has been verified.']);
            }
            else{
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => false, 'message' => 'This transaction has already been processed or does not exist.'];
            }
        }        
        else if($request->approved == 'declined'){
            $transactions = PartnerTransaction::find($request->id);
            $buyer = User::find($request->buyer_id);
            if($transactions->status == 0 && !empty($transactions) && !empty($buyer)){
                $transactions->status = 2;
                $transactions->partner_comment = $request->partner_comment;
                $transactions->process_by = \Auth::user()->id;
                $transactions->approved_rejected_date = $dateNow;
                $transactions->save();
                // $buyer->credits += $transactions->amount;
                // $buyer->save();  
                $buyer->increment('credits', $transactions->amount);

                $fee = Fee::where(['meta_value' => $transactions->id, 'meta_key' => 'cashout-partner'])->first();
                $fee->collected = 0.00;
                $fee->save();
                $donation = PartnerDonation::where('partner_transaction_id', $transactions->id)->first();
                $donation->user_id = \Auth::user()->id;
                $donation->amount = 0.00;
                $donation->save();

                Cache::store('redis_svr03')->forget($cacheKey);
                return response()->json(['success' => true, 'message'=>'Cashout code '.$transactions->code.' has been rejected.']);
            }
            else{
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => false, 'message' => 'This transaction has already been confirmed or does not exist.'];
            }
        }
    }

    public function sendDepositOnPartner(Request $request) 
    {

        /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */
        $user_id = \Auth()->id();
        $cacheKey = 'cashoutDepositBetRequest_' . $user_id;

        if ( !Cache::store('redis_svr03')->has($cacheKey)) {

            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);

            $rules = [
                'amount_partner' => $request->type == 'deposit' ? 'required|numeric|min:500' : 'required|numeric|min:1000',
                'partner' => 'required|numeric',
            ];
    
            $messages = [
                'amount_partner.min' => $request->type == 'deposit' ? 'The minimun deposit credit request is 500 EZ credits.' : 'The minimun cashout credit request is 1000 EZ credits.',
                'amount_partner.required' => 'The EZ credits field is required.',
                'partner.required' => 'Please select a partner to send '.$request->type.' transaction request.',
            ];
    
            $voucherOwner = null;
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->passes()){
    
                try {
    
                    if ($request->type == 'cashout') {
                        \App\User::where('id', $user_id)->lockForUpdate()->get();
                    }
        
                    $partner = Partner::find($request->partner)->load('userOwner', 'province');
                    $partnerPrefix = !empty( $partner ) ? strtoupper(substr( preg_replace("/\s+/", "",$partner->partner_name), 0, 3)) : '';
                    $parentPartner = $partner->parentPartner;
                
                    $last_trans = Transaction::where(['user_id' => \Auth::user()->id ,'status' => 'processing', 'type' => $request->type])->first();
                    $last_parts = PartnerTransaction::where(['user_id' => \Auth::user()->id, 'trade_type' => 'partner-user', 'status' => 0, 'type' => $request->type])->first();
                
                    if($last_trans || $last_parts){
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return [
                            'success' => false, 
                            'message' => 'You still have a pending '.$request->type.' request for today. Please contact our admins regarding in this transaction.'
                        ];
                    }
        
                    if ((\Auth::user()->credits < 0 || \Auth::user()->credits < $request->amount_partner) && $request->type == 'cashout') {
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return [
                            'success' => false,
                            'message' => 'Insufficient Ez credits'
                        ];
                    }
        
                    $user = \Auth::user();

                    $transactions = new PartnerTransaction;
                    $transactions->code = $this->generateCode($request->type, $partnerPrefix);
                    $transactions->type = $request->type == 'deposit' ? 'deposit' : 'cashout';
                    $transactions->trade_type = 'partner-user';
                    $transactions->partner_id = $request->partner;
                    $transactions->user_id = $user->id;
                    $transactions->amount = $request->amount_partner;
                    
                    //change earnings; if voucher code exists and its affliated with this partner
                    //then only give 2% earnings instead of 3%
                    //NEW CHANGE: APRIL 2021 -> IF PARTNER IS TIM'S AGENT; USE 2.5%
                    if($request->type == 'deposit'){
                        
                        $transactions->voucher_code = $user->redeem_voucher_code; //new method for voucher code
                        if(!empty($user->redeem_voucher_code)){
                            $voucherOwner = \App\User::whereRaw("voucher_code = '{$user->redeem_voucher_code}'")->first();
                        } 

                        $isTimPartnerAgent = $partner->id == env('TIMS_AGENT_PARTNER_ID');
                        $partnerDepositCommissions = $isTimPartnerAgent ? env('TIMS_PARTNER_DEPOSIT_COMMISSIONS') : ( empty($voucherOwner) ? env('PARTNER_DEPOSIT_COMMISSIONS') : env('PARTNER_DEPOSIT_COMMISSIONS_VIA_AFFILIATES') );
                        
                        // $transactions->partner_earnings = empty($voucherOwner) ? ($transactions->amount * env('PARTNER_DEPOSIT_COMMISSIONS') ) : ($transactions->amount * env('PARTNER_DEPOSIT_COMMISSIONS_VIA_AFFILIATES'));
                        $transactions->partner_earnings = $transactions->amount * $partnerDepositCommissions;

                        if(!$isTimPartnerAgent){
                        //check if this partner is under a parent partner 
                            //which means that the parent partner will also earn a commissions from this transaction
                            if(!empty($parentPartner)){
                                $parentCommissionPercentage = env('PARTNER_COMMISSIONS_FROM_SUB') ? env('PARTNER_COMMISSIONS_FROM_SUB') : 0.008;
                                $transactions->main_partner_id = $parentPartner->partner_id;
                                $transactions->main_partner_earnings = $transactions->amount * $parentCommissionPercentage;
                            }
                        }
        
                    }else{ //else cashout
        
                         /**
                         * Set fee percentages & multiplier
                         */
                        $cashoutFee = env('CASHOUT_FEE',0.05); 
                        $isTimPartnerAgent = $partner->id == env('TIMS_AGENT_PARTNER_ID');
                        //check if its TIM'S agent/partner; if true, use different number
                        $partnerCashoutCommission = $isTimPartnerAgent ? env('TIMS_PARTNER_CASHOUT_COMMISSIONS',0.025) :  env('PARTNER_CASHOUT_COMMISSIONS',0.03);


                        /**
                         * End Set fee percentages & multiplier
                        */
                        
                        $transactions->partner_earnings =  ($transactions->amount - ($transactions->amount * $cashoutFee )) * $partnerCashoutCommission;
        
                        //CHECK IF PARTNER IS TIM'S AGENT; IF ITS TIMS AGENT, NO NEED TO GIVE COMMISSION VIA TRANSACTION
                        //because OGC earns from per bet of TIM's bettors
                        
                        if(!$isTimPartnerAgent){
                            //check if this partner is under a parent partner 
                            //which means that the parent partner will also earn a commissions from this transaction
                            if(!empty($parentPartner)){
                                $parentCommissionPercentage = env('PARTNER_COMMISSIONS_FROM_SUB') ? env('PARTNER_COMMISSIONS_FROM_SUB') : 0.008;
                                $transactions->main_partner_id = $parentPartner->partner_id;
                                $transactions->main_partner_earnings = ($transactions->amount - ($transactions->amount * env('CASHOUT_FEE'))) * $parentCommissionPercentage;
                            }
                        }


                    }
        
                
                    $transactions->status = 0;
                    $transactions->data = json_encode(['mop' => $request->mop]);
        
        
                    if ($request->type == 'cashout') {
        
                        $latestDeposits = Transaction::where([
                                                                'user_id' => \Auth::user()->id ,
                                                                'status' => 'completed', 
                                                                'type' => 'deposit'
                                                            ])->orderBy('id','DESC')->take(10)->get()->toArray();
        
                        $latestPartnerDeposits = PartnerTransaction::where([
                                                                'user_id' => \Auth::user()->id ,
                                                                'status' => 1,
                                                                'trade_type' => 'partner-user'
                                                            ])->orderBy('id','DESC')->take(20)->get()->toArray();
        
                        $mergeDeposits = array_merge($latestDeposits,$latestPartnerDeposits);
                        usort($mergeDeposits, function ($a, $b) {
                            return $b['created_at'] <=> $a['created_at'];
                        });
        
                        $recentlyDepositedOnThisPartner = false;
        
                        foreach($mergeDeposits as $key => $deposit){
                            if($key <= 9){
                                if(isset($deposit['partner_id']) && $deposit['partner_id'] == $request->partner){
                                    $recentlyDepositedOnThisPartner = true;
                                    break;
                                }
                            }else{
                                break;
                            }
                        }
        
                        if(!$recentlyDepositedOnThisPartner ){ //if no recent transacrtion with partner then
                            $transactions->status = -1; //meaning admin has to confirm first that this cashout is legit
                        }
        
                        $latestDeposits = Transaction::where(['user_id' => \Auth::user()->id ,'status' => 'completed', 'type' => 'deposit'])->orderBy('id','DESC')->take(10)->get();
                        $latestPartnerDeposits = PartnerTransaction::where(['user_id' => \Auth::user()->id ,'status' => 1, 'type' => 'deposit','trade_type' => 'partner-user'])->orderBy('id','DESC')->take(10)->get();
        
                        
                        // $user->credits -= $transactions->amount;
                        $user = User::find(\Auth::user()->id);
                        $user->decrement('credits', $transactions->amount);
                        $user->save();
                        $transactions->save();
                        $donation = new PartnerDonation;
                        $donation->partner_transaction_id = $transactions->id;
                        $donation->user_id = \Auth::user()->id;
                        $donation->amount = $request->donation;
                        $donation->save();
                        // Fee
                        $fee = new Fee;
                        $fee->meta_key = 'cashout-partner';
                        $fee->meta_value = $transactions->id;
                        // $fee->percent = '0.05';
                        $fee->percent = $cashoutFee;
                        $fee->collected = $request->donation;
                        $fee->save();
                    }else if($request->type == 'deposit'){
                        $transactions->save();
                    }
                            
                    $data = [
                            'id' => $transactions->id,
                            'amount' => $transactions->amount,
                            'code' => $transactions->code,
                            'partner' => $partner->partner_name,
                            'address' => $partner->address.','.$partner->province->province,
                            'operation' => $partner->operation_time
                        ];
     
                    Cache::store('redis_svr03')->forget($cacheKey);
                    return [
                        'success' => true, 
                        'message' => 'Transaction - '.$request->type.' code '.$transactions->code." has been sent to the partner. Please wait for the partner's response", 
                        'data' => $data
                    ];
        
                } catch (\Exeception $e) {
                    Cache::store('redis_svr03')->forget($cacheKey);
                    throw $e;
                }
    
            }else{
                Cache::store('redis_svr03')->forget($cacheKey);
                return ['success' => false, 'errors' => $validator->errors()];
            }                
        }else{
        
            return [
                'success' => false, 
                'message' => 'Cashout request failed. Please try again. Error Code: CO0820',
            ];
        }

    
    }

    public function sendDepositOnAdmin(Request $request){
        $transaction = new PartnerTransaction;

        $rules = [
            'amount' => 'required|numeric|min:10000',
            'provider' => 'required|in:BDO-deposit,BPI-deposit,cash-others',
            'account_name' => 'required_if:provider_type,bank',
            'account_number' => 'required_if:provider_type,bank',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user = User::find(\Auth::user()->id);

            if ((\Auth::user()->userPartner->partner_credits < 0 || \Auth::user()->userPartner->partner_credits < $request->amount) && $request->type == 'cashout') {
                return ['success' => false, 'message' => 'Insufficient Partner Ez credits'];
            }

            $transaction->code = $this->generateCode($request->type);
            $transaction->type = $request->type;
            $transaction->trade_type = 'partner-admin';
            $transaction->partner_id = User::find(\Auth::user()->id)->userPartner->id;
            $transaction->remaining_credits = \Auth::user()->userPartner->partner_credits ? \Auth::user()->userPartner->partner_credits : 0;
            $transaction->user_id = \Auth::user()->id;
            $transaction->status = 0;
            $transaction->data = json_encode(['mop' => $request->provider]);
            $transaction->amount = $request->amount;
            $transaction->save();
            
            if ($request->type == 'cashout') {
                $partner = $user->userPartner;
                $partner->partner_credits -= $transaction->amount;
                $partner->save();
            }

            return ['success' => true, 'transaction' => $transaction];
        } else {
            return ['success' => false, 'errors' => $validator->errors()];
        }
    }

    public function sendDepositOnParent(Request $request){
        $transaction = new PartnerTransaction;

        $rules = [
            'amount' => 'required|numeric|min:10000',
            'provider' => 'required|in:BDO-deposit,BPI-deposit,cash-others',
            'account_name' => 'required_if:provider_type,bank',
            'account_number' => 'required_if:provider_type,bank',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user = User::find(\Auth::user()->id);
            //$last_parts = PartnerTransaction::where(['user_id' => $user->id, 'partner_id' => $user->userPartner->id, 'trade_type' => 'partner-sub', 'status' => 0, 'type' => $request->type])->first();
            $partner = $user->userPartner;
            $partnerPrefix = !empty( $partner ) ? strtoupper(substr( preg_replace("/\s+/", "",$partner->partner_name), 0, 3)) : '';
            if(empty($partner)){
                return ['success' => false, 'errors' => ['message' => 'This partner does not exists.' ] ];
            }

            // if($last_parts){
            //     return ['success' => false, 'message' => 'You still have a pending '.$request->type.' request for today. Please contact your agent regarding in this transaction.'];
            // }

            if (( $partner->partner_credits < 0 || $partner->partner_credits < $request->amount) && $request->type == 'cashout') {
                return ['success' => false, 'message' => 'Insufficient Partner Ez credits'];
            }

            $transaction->code = $this->generateCode($request->type, $partnerPrefix);
            $transaction->type = $request->type;
            $transaction->trade_type = 'partner-sub';
            $transaction->partner_id = $partner->id;
            $transaction->main_partner_id = $request->main_partner_id;
            $transaction->remaining_credits = $partner->partner_credits ? $partner->partner_credits : 0;
            $transaction->user_id = \Auth::user()->id;
            $transaction->status = 0;
            $transaction->data = json_encode(['mop' => $request->provider]);
            $transaction->amount = $request->amount;
            $transaction->save();
            
            if ($request->type == 'cashout') {
                // $partner->partner_credits -= $transaction->amount;
                $partner->decrement('partner_credits', $transaction->amount);
            }

            return ['success' => true, 'transaction' => $transaction];
        } else {
            return ['success' => false, 'errors' => $validator->errors()];
        }
    }

    public function uploadPicture(Request $request)
    {
        // $transaction = PartnerTransaction::where(['id' => $request->id, 'status' => $request->status])->first();
        $transaction = PartnerTransaction::where(['id' => $request->id])->first();
        $rules = [
            'photo' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) {     
            $thumb = "";
            if ($request->photo) {

                $name = $transaction->code . '-' . $transaction->trade_type . '-' .  str_random(20) . time() . '.jpg';
                $base64 = substr($request->photo, strpos($request->photo,',')+1);

                // This saves the base64encoded destinationPath
                file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));

                $file = storage_path() . '/uploads/' . $name;
                $thumb = 'thumb_' . $name;

                $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);

                $transaction->picture = '/uploads/' . $name;
            }

            // if(isset($request->status)){
            //     $transaction->status = $request->status;
            // }
            
            if (\Auth::user()->isAdmin()) {
                $transaction->process_by = \Auth::user()->id;
            }else{

                //$transaction->user_id = \Auth::user()->id;

            }

            $transaction->save();
            return ['success' => true];
        }else{
            return ['success' => false,'errors' => $validator->errors()];
        }
        
    }

    private function generateCode($type, $prefix = '') 
    { 
    	do{
	    	$type_code = $type == 'deposit' ? 'BC' : 'CO';
		    $chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ"; 
		    srand((double)microtime()*1000000); 
		    $i = 0; 
		    $pass = '' ; 

		    while ($i <= 9) { 
		        $num = rand() % 33; 
		        $tmp = substr($chars, $num, 1); 
		        $pass = $pass . $tmp; 
		        $i++; 
		    }
		    $code = $type_code.'-'. $prefix .$pass;
    	}while (!\App\PartnerTransaction::where('code',$code)->get()->isEmpty());

	    return $code; 

    }

    private function payoutCode() 
    { 
    	do{
	    	$type = 'PO';
		    $chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ"; 
		    srand((double)microtime()*1000000); 
		    $i = 0; 
		    $pass = '' ; 

		    while ($i <= 9) { 
		        $num = rand() % 33; 
		        $tmp = substr($chars, $num, 1); 
		        $pass = $pass . $tmp; 
		        $i++; 
		    }
		    $code = $type.'-'.$pass;
    	}while (!\App\PartnerTransaction::where('code',$code)->get()->isEmpty());

	    return $code; 

    }
    
    public function setPartnerInformation(Request $request){
        $partner = !\Auth::user()->userPartner ? new Partner : Partner::where('user_id', \Auth::user()->id)->first();

        if(empty($partner)){
            abort(404);
        }

        $rules = [
            'partner_name' => 'required',
            'mobile_number' => 'required|numeric',
            'email' => 'required|email',
        ];

        $messages = [
            'partner_name.required' => 'The partner name field is required.',
            'mobile_number.required' => 'The account type field is required.',
            'mobile_number.numeric' => 'The mobile number field must be numeric.',
            'email.required' => 'The email field is required.',
            'email.email' => 'This is not an email format.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->passes()){
            $partner->partner_name = $request->partner_name;
            $partner->user_id = \Auth::user()->id;
            $partner->mobile_number = $request->mobile_number;
            $partner->landline_number = $request->landline_number;
            $partner->contact_person = $request->contact_person;
            $partner->email = $request->email;
            $partner->bpi_account = $request->bpi_account;
            $partner->bpi_account_name = $request->bpi_account_name;
            $partner->bdo_account = $request->bdo_account;
            $partner->bdo_account_name = $request->bdo_account_name;
            $partner->cashout_choice = null;
            $partner->operation_time = $request->operation_time;
            $partner->payment_mode = $request->payment_mode;
            $partner->details = $request->details;
            $partner->address = $request->address;
            $partner->province_id = $request->province_id;
            $partner->facebook_link = $request->facebook_link;
            $partner->verified = !\Auth::user()->userPartner ? 0 : $partner->verified;
            $partner->active = !\Auth::user()->userPartner ? 0 : $partner->active;
            $partner->save();

            return response()->json(['success' => true, 'partner' => $partner]);
        }else{
            return response()->json(['success' => false, 'error' => $validator->errors()]);
        }
    }

    public function updatePartnerInformation(Request $request){
        $partner = Partner::where('user_id', $request->user_id)->first();

        if(empty($partner)){
            abort(404);
        }

        $rules = [
            'partner_name' => 'required',
            'mobile_number' => 'required|numeric',
            'email' => 'required|email',
        ];

        $messages = [
            'partner_name.required' => 'The partner name field is required.',
            'mobile_number.required' => 'The account type field is required.',
            'mobile_number.numeric' => 'The mobile number field must be numeric.',
            'email.required' => 'The email field is required.',
            'email.email' => 'This is not an email format.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->passes()){
            $partner->partner_name = $request->partner_name;
            $partner->user_id = $request->user_id;
            $partner->mobile_number = $request->mobile_number;
            $partner->landline_number = $request->landline_number;
            $partner->contact_person = $request->contact_person;
            $partner->email = $request->email;
            $partner->bpi_account = $request->bpi_account;
            $partner->bpi_account_name = $request->bpi_account_name;
            $partner->bdo_account = $request->bdo_account;
            $partner->bdo_account_name = $request->bdo_account_name;
            $partner->cashout_choice = null;
            $partner->operation_time = $request->operation_time;
            $partner->payment_mode = $request->payment_mode;
            $partner->details = $request->details;
            $partner->address = $request->address;
            $partner->province_id = $request->province_id;
            $partner->facebook_link = $request->facebook_link;
            $partner->save();

            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false, 'error' => $validator->errors()]);
        }
    }

    public function setPartnerLogo(request $request){

        $user = \Auth::user();
        if($user->isAdmin()){
            $partner = Partner::where('user_id', $request->user_id)->first() ? Partner::where('user_id', $request->user_id)->first() : new Partner;
        }else{
            if($user->id == $request->user_id){
                $partner = Partner::where('user_id', $user->id)->first() ? Partner::where('user_id', $user->id)->first() : new Partner;
            }else{
                abort(404);
            }
        }
    
        $rules = [
            'photo' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) {     
            $thumb = "";
            if ($request->photo) {
                $name = str_random(20) . time() . '.jpg';
                $base64 = substr($request->photo, strpos($request->photo,',')+1);
                // This saves the base64encoded destinationPath
                file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));
                $file = storage_path() . '/uploads/' . $name;
                $thumb = 'thumb_' . $name;
                $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);
                $partner->logo = '/uploads/' . $name;
            }
            $partner->user_id = $request->user_id;
            $partner->verified = $partner != null ? $partner->verified : 0;
            $partner->active = $partner != null ? $partner->active : 0;
            $partner->save();
            return ['success' => true, 'partner' => $partner];
        }else{
            return ['success' => false,'errors' => $validator->errors()];
        }
    }

    public function partnerDiscrepancy(Request $request)
    {
        if(\Auth::user()->isAdmin()){

            $dateNow = date('Y-m-d H:i:s');
            $transaction = PartnerTransaction::find($request->id)->load('discrepancy');

            if($transaction && $transaction->status == 0){
                $partner = User::find($request->user_id)->userPartner;
                $discrepancy = new PartnerDiscrepancy;
                $rules = [
                    'message' => 'required',
                    'photo' => 'required',
                ];
        
                $validator = Validator::make($request->all(),$rules);
                if ($validator->passes()) { 
                    $active = $this->activePartner($partner->id);
                    if(!$active)
                    {
                        return['success' => false, 'message' => 'Transactions with partner <b style="color: red;">'.$partner->partner_name.'</b> are currently disabled due to deactivation.' ];
                    }
                    else
                    {
                        if ($request->amount) {
                            $partner->partner_credits += $request->amount;
                            $discrepancy->amount = $request->amount;
                        }else{
                            $partner->partner_credits += $transaction->amount;
                        } 
                        $transaction->new_credits = $partner->partner_credits;
            
                        if ($request->photo) {
            
                            $name = str_random(20) . time() . '.jpg';
                            $base64 = substr($request->photo, strpos($request->photo,',')+1);
            
                            // This saves the base64encoded destinationPath
                            file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));
            
                            $file = storage_path() . '/uploads/' . $name;
                            $thumb = 'thumb_' . $name;
            
                            $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);
                            $discrepancy->picture = '/uploads/' . $name;
                            $transaction->picture = '/uploads/' . $name;
                        }
                        $partner->save();
                        $discrepancy->partner_transaction_id = $request->id;
                        $discrepancy->user_id = \Auth::user()->id;
                        $discrepancy->partner_id = $request->partner_id;
                        $discrepancy->message = $request->message;
                        $discrepancy->mop = $request->mop;
                        $discrepancy->save();
                        $transaction->partner_comment = $request->message;
                        $transaction->process_by = \Auth::user()->id;
                        $transaction->approved_rejected_date = $dateNow;
                        $transaction->status = 1;
                        $transaction->save();
                        return ['success' => true,'transaction' => $transaction];
                    }
                }else{
                    return ['success' => false,'errors' => $validator->errors()];
                }
            }
            else{
                return ['success' => false, 'message' => 'This transaction has already been confirmed or does not exist.'];
            }


        }else{
            abort(404);
        }

    }

    public function adminExtraActionOnPartner(Request $request)  
    {
        if(\Auth::user()->isAdmin()){

            $dateNow = date('Y-m-d H:i:s');
            $transaction = PartnerTransaction::find($request->id)->load('discrepancy');
            $partner = User::find($request->user_id)->userPartner;
            $discrepancy = new PartnerDiscrepancy;
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
                $active = $this->activePartner($partner->id);
                if(!$active)
                {
                    return['success' => false, 'message' => 'Transactions with partner <b style="color: red;">'.$partner->partner_name.'</b> are currently disabled due to deactivation.' ];
                }
                else
                {
                    if ($request->amount && $transaction->type == 'deposit') {
                        if ($request->amount > $last_amount) {
                            $add_to_credits = $request->amount - $last_amount;
                            $partner->partner_credits += $add_to_credits;
                        }else{
                            $minus_to_credits = $last_amount - $request->amount;
                            $partner->partner_credits -= $minus_to_credits;
        
                        }
                        $discrepancy->amount = $request->amount;
                    }
                    $transaction->new_credits = $partner->partner_credits;
        
                    if ($request->photo) {
        
                        $name = str_random(20) . time() . '.jpg';
                        $base64 = substr($request->photo, strpos($request->photo,',')+1);
        
                        file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));
        
                        $file = storage_path() . '/uploads/' . $name;
                        $thumb = 'thumb_' . $name;
        
                        $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);

                        if ($transaction->type == 'deposit') {
                            $discrepancy->picture = '/uploads/' . $name;
                            $transaction->picture = '/uploads/' . $name;
                        }else{
                            $transaction->picture = '/uploads/' . $name;
                        }
                    }
                    $partner->save();
                    if ($transaction->type == 'deposit') {
                        $discrepancy->partner_transaction_id = $request->id;
                        $discrepancy->user_id = \Auth::user()->id;
                        $discrepancy->partner_id = $request->partner_id;
                        $discrepancy->message = $request->message;
                        $discrepancy->mop = $request->mop;
                        $discrepancy->save();
                    }else{
                        $transaction->process_by = \Auth::user()->id;
                    }
                    $transaction->partner_comment = $request->message;
                    $transaction->status = 1;
                    $transaction->approved_rejected_date = $dateNow;
                    $transaction->save();
                    return ['success' => true,'trnasaction' => $transaction];
                }

            }else{
                return ['success' => false,'errors' => $validator->errors()];
            }

            return ['success' => true];

        }else{
            abort(404);
        }

    }

    public function processPayout(Request $request) 
    {
        if(\Auth::user()->isAdmin()){

            $partner = Partner::find($request->id);
            $rules = [
                'message' => 'required',
                'photo' => 'required',
            ];
            $messages = [
                'photo.required' => 'Receipt is required. Please attach an image file for it.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if($validator->passes()){
                if( $partner->partner_earnings < 1000 ){
                    return ['success' => false, 'message' => "Partner's earnings must be at least &#8369; 1,000 in order to process his/her payout.", "shit" => $partner->partner_earnings];
                }
                else{
                    $payout_amount = $request->amount;
    
                    $payout = new PartnerPayout;
                    if ($request->photo) {
                        $name = str_random(20) . time() . '.jpg';
                        $base64 = substr($request->photo, strpos($request->photo,',') + 1);
                        file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));
                        $file = storage_path() . '/uploads/' . $name;
                        $thumb = 'thumb_' . $name;
                        $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);
                        $payout->receipt = '/uploads/' . $name;
                    }
                    $payout->code = $this->payoutCode();
                    $payout->partner_id = $partner->id;
                    $payout->user_id = $partner->user_id;
                    $payout->earnings = $payout_amount;
                    $payout->process_by = \Auth::user()->id;
                    $payout->data = json_encode(['mop' => $partner->cashout_choice.'-deposit']);

                    $payout->message = $request->message;
                    $payout->save();

                    // $partner->partner_earnings = 0;
                    // $partner->save();

                    $partner->decrement('partner_earnings', $payout_amount);

                    if(!empty($request->useCredits)){
                        $transaction = new PartnerTransaction;
                        $transaction->code = $this->generateCode('deposit');
                        $transaction->type = 'deposit';
                        $transaction->trade_type = 'partner-admin';
                        $transaction->partner_id = $partner->id;
                        $transaction->remaining_credits = $partner->partner_credits;
                        $transaction->user_id = $partner->user_id;
                        $transaction->status = 0;
                        $transaction->data = json_encode(['mop' =>  'payout-deposit']);
                        $transaction->amount = $payout_amount;
                        $transaction->save();                        
                    }

                    
                  
                    return ['success' => true, 'message' => "Payout transaction has been processed. <span style='font-weight:bold;color: #820804'>".$partner->partner_name."</span>'s earnings has been reset to zero."];
                } 
            }
            else{
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }   

        }else{
            abort(404);
        }

        
        
    }

    public function publicProfile(Request $request){

        $partner = User::find(\Auth::user()->id)->userPartner;

        if(!empty($partner)){

            $profile = $partner->profile != null ? PartnerProfile::where('partner_id', $partner->id)->first() : new PartnerProfile;
            if($request->title == '' || $request->title == null){
                $request->title = $partner->partner_name;
            }
            $profile->partner_id = $partner->id;
            $profile->user_id = \Auth::user()->id;
            $profile->title = $request->title;
            $profile->content = $request->content;
            $profile->save();

            return ['success' => true, 'message' => "Your public profile has been added/updated. Anyone can see your published profile in the deposit and cashout page."];
        
        }else{
            abort(404);
        }

    }

    public function downloadTransactions(Request $request)
    {
        $partner = \Auth::user()->userPartner;
        $isAdmin = \Auth::user()->isAdmin();

        if(!empty($partner) || $isAdmin){

            $from_date = $request->fromDate;
            $to_date = $request->toDate;
            $status = $request->status;
            $partner_id = $isAdmin ? $request->partner_id : $partner->id;
            $type = !is_array($request->type) ? [$request->type] : $request->type;
            $tradeType = $request->tradeType;

            $where = [
                [ 'approved_rejected_date', '>=', $from_date ],
                [ 'approved_rejected_date', '<=', $to_date ],
                [ 'partner_id', '=', $partner_id],
                [ 'trade_type', '=', $tradeType ],
            ];

            $transactions = PartnerTransaction::where($where)
                                ->whereIn('type', $type)
                                ->whereIn('status', $status)->get();

            $downloadName = $partner_id . ' - Transactions Download - ' . implode("-",$type) .' - ' . date('Y-m-d H:i:s');

            return \Excel::create( $downloadName, function($excel) use($transactions) {

                $excel->sheet('Overview', function($sheet) use($transactions) {
                    $sheet->loadView('agent.downloads.transactions', array('transactions' => $transactions));
                });
            })->store('xls', false, true);

        }else{
            abort(404);
        }

    }

    public function downloadTransactionFile(Request $request)
    {
        $partner = \Auth::user()->userPartner;
        $isAdmin = \Auth::user()->isAdmin();

        if(!empty($partner) || $isAdmin){
            $file = $request->file;
            return response()->download(storage_path("exports/{$file}"));
        }else{
            abort(404);
        }

    }

    public function downloadTransactionsView(Request $request)
    {
    
        $partner = \Auth::user()->userPartner;
        $isAdmin = \Auth::user()->isAdmin();

        if(!empty($partner) || $isAdmin){
            $from_date = $request->fromDate;
            $to_date = $request->toDate;
            // $status = $request->status;
            $partner_id = $partner->id;

            $status = [0,1,2];

            $where = [
                [ 'created_at', '>=', $from_date ],
                [ 'created_at', '<=', $to_date ],
                [ 'partner_id', '=', $partner_id]
            ];

            $transactions = PartnerTransaction::where($where)->whereIn('status', $status)->get();

            return view('agent.downloads.transactions',compact('transactions')); 
            
        }else{
            abort(404);
        }        


    }

    public function markAsVerified(Request $request)
    {

        $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();
        $user = \Auth::user();
        $partner = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

            return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
        
        });


        $user_id = $request->user_id;
        $partner_id = $partner->id;
        $verified_by_user_id = \Auth::id();

        if(!empty($partner)){

            if(!empty($user_id)){

                $verified = \App\VerifiedBy::firstOrCreate([
                    'user_id' => $user_id,
                    'verified_type' => 'partner',
                    'verified_by' => $partner_id,
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
            
        }else{
            abort(404);
        }


    
    }

    public function setVoucherAsTims(Request $request)
    {
        
        $partnershipCacheKey = 'agentPartnership_' . getUserCacheKey();
        $user = \Auth::user();
        $partner = Cache::remember( $partnershipCacheKey, 120 , function () use ($user){

            return isPartnerSubUser($user) ?  $user->subUserPartner->partner : $user->userPartner;
        
        });


        $user_id = $request->user_id;
        $partner_id = $partner->id;

        $bettorUser = \App\User::find($user_id);

        if(!empty($partner) && $bettorUser &&  $partner->id == env('TIMS_AGENT_PARTNER_ID')){


            $where = [
                'user_id' => $user_id,
                'partner_id' => $partner->id,
                'trade_type' => 'partner-user',
                'type' => 'deposit'
            ];

            $updateTransactiosn = PartnerTransaction::where($where)->update(
                [
                    'voucher_code' => 'TIMS'
                ]
                );

             \App\User::where('id', $user_id)->update(['redeem_voucher_code' => 'TIMS']);

            return [
                'success' => true,
                'message' => 'Successfully updated user voucher code.'
            ];
        
            
        }else{
            abort(404);
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
}
