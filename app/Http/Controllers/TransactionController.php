<?php

namespace App\Http\Controllers;

use Validator;
use Image;
use Illuminate\Http\Request;
use App\Transaction;
use App\TransactionDiscrepancy;
use App\TransactionNote;
use App\User;
use App\Donation;
use App\Commission;
use App\CommissionsPartner;
use App\CommissionsBets;
use App\DepositCommission;
use App\Wallet;
use Datatables;
use App\ReportedBug;
use App\Promotion;
use App\Fee;
use Cache;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function deposit(Request $request, $via = "direct", $partner_id = '')
    {
        $partners = \App\Partner::all();
        $provinces = \App\Province::all();
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

        /**
         * Removed so that we won't show the banks available in our site
         */
        // foreach(\App\SiteSetting::where('status', 1)->get() as $siteSettings) {
        //     if(isset($settings[$siteSettings->name])) {
        //         $settings[$siteSettings->name] = $siteSettings->value;
        //     }
        // }

        //new method no need for voucher code
        //$voucher_code = $request->input('v') ? $request->input('v') : '';

        $voucher_code = '';
        $autoSelectedPartner = !empty($partner_id) ? \App\Partner::find($partner_id)->load('province', 'profile', 'affliates.user') : null;

    	return view('user.deposit', compact('partners', 'provinces', 'settings', 'via', 'partner_id', 'voucher_code', 'autoSelectedPartner'));
    }
    
    public function showTos() {
        return \Auth::user()->accept_tos ? 'N/A' : \App\LegalDocument::where('type', 'tos')->first()->contents;
    }
    
    public function ackTos() {
        $user = \Auth::user();
        $user->accept_tos = 1;
        $user->save();
        return ['success' => true];
    }

    public function cashout($via = "direct", $partner_id = '')
    {

        $partners = \App\Partner::all();
        $autoSelectedPartner = !empty($partner_id) ? \App\Partner::find($partner_id)->load('province', 'profile', 'affliates.user') : null;

    	return view('user.cashout', compact('via', 'partner_id', 'partners', 'autoSelectedPartner'));
    }
    
    public function history()
    {
        return view('user.history');
    }
    
    public function getTransactionImage($filename)
    {
        return Image::make(storage_path('uploads/' . $filename))->response();
    }

    public function createUpdate(Request $request)
    {
         /** Change database to use write connection - for transactions that involves changes with user credits **/
        \DB::setDefaultConnection('mysql_transaction');
        /** end change db */
        $user_id = \Auth()->id();

        $cacheKey = 'cashoutDepositBetRequest_' . $user_id;

        if ( !Cache::store('redis_svr03')->has($cacheKey)) {

            Cache::store('redis_svr03')->put($cacheKey, 'processing', 1);

            $voucherOwner = null;
            $completedDepositsCount = 0;

            $rules = [
                'amount' => $request->type == 'deposit' ? 'required|numeric|min:500' : 'required|numeric|min:1000',
                'provider' => 'required|in:BDO-deposit,CoinsPH-payment,BPI-deposit,Metrobank-deposit,Securitybank-deposit,cebuana-remittance,mlhuiller-remittance,lbc-remittance,palawan-remittance,western-remittance,BDO-online,others,others-deposit,BPI-online,Metrobank-online,Securitybank-online',
                'account_name' => 'required_if:provider_type,bank',
                'account_number' => 'required_if:provider_type,bank',
                'account_type' => 'required_if:provider_type,bank',
                'account_currency' => 'required_if:provider_type,bank',
                'full_name' => $request->provider != 'CoinsPH-payment' ? 'required_if:provider_type,remittance,' : 'sometimes|nullable|string',
                'mobile_number' => 'required_if:provider_type,remittance,',
                'location' => 'required_if:provider_type,remittance',
                'type' => 'required|in:deposit,cashout'
            ];

            $messages = [
                'account_name.required_if' => 'The account name field is required.',
                'account_type.required_if' => 'The account type field is required.',
                'account_number.required_if' => 'The account number field is required.',
                'account_currency.required_if' => 'The account currency field is required.',
                'full_name.required_if' => 'The full name field is required.',
                'mobile_number.required_if' => 'The mobile number field is required.',
                'coinsph_user.required_if' => 'This field is required for Coins.Ph payments.',
                'location.required_if' => 'The location field is required.',
                'amount.min' => $request->type == 'deposit' ? 'The minimun deposit credit request is 500 ez credits.' : 'The minimun cashout credit request is 1,000 ez credits.',
                'amount.required' => 'The ez credits field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->passes()) {
                
                if($request->type == 'deposit'){


                    //new method no need to check voucher code
                    // if ($request->voucher_code) {
                    //     $voucherOwner = \App\User::whereRaw("voucher_code = '{$request->voucher_code}'")->first();
        
                    //     if (empty($voucherOwner)){
                    //         Cache::store('redis_svr03')->forget($cacheKey);
                    //         return ['success' => false, 'message' => 'Voucher code does not exist'];
                    //     }
                    // }

                    $_trans = Transaction::where(['user_id' => \Auth::user()->id,'status' => 'processing', 'type' => 'deposit'])->first();
                    $_parts = \App\PartnerTransaction::where(['user_id' => \Auth::user()->id, 'trade_type' => 'partner-user', 'status' => 0, 'type' => 'deposit'])->first();
                    if($_trans || $_parts){
                        Cache::store('redis_svr03')->forget($cacheKey);
                        return ['success' => false, 'message' => 'You already have a pending deposit'];
                    }

                    //new method no need to check voucher code
                    // if($request->voucher_code){
                    //     if($request->voucher_code == \Auth::user()->voucher_code){
                    //         Cache::store('redis_svr03')->forget($cacheKey);
                    //         return ['success' => false, 'message' => 'You can\'t used your own voucher code'];
                    //     }
                    // }

                    $completedDepositsCount =  Transaction::where(['user_id' => \Auth::user()->id,'status' => 'completed', 'type' => 'deposit'])->count();

                }
                // Limit transactions to 5 counts
                // $limit = Transaction::where('user_id',\Auth::user()->id)
                //                     ->whereDate('created_at',date('Y-m-d'))
                //                     ->count();
                $limit = 0; //removed limit
                // if($limit >= 5){
                //     Cache::store('redis_svr03')->forget($cacheKey);
                //     return ['success' => false, 'message' => 'You exceeded the number of (5) transactions per day'];
                // }
                if(\Auth::user()->id == 1085) {
                    Cache::store('redis_svr03')->forget($cacheKey);
                    return ['success' => false, 'message' => 'Temporarily disabled, please contact admin.'];
                }
                // if ($request->type == 'cashout') { # Temporarily close site
                //     return ['success' => false, 'message' => 'We are currently in open beta. You cannot cashout/deposit credits yet. You may register to participate in the open beta and win awesome prizes. For more info, join our 2ez.bet community.  <a href="https://web.facebook.com/groups/2ez.bet">https://web.facebook.com/groups/2ez.bet</a>'];
                // }

                if ((\Auth::user()->credits < 0 || \Auth::user()->credits < $request->amount) && $request->type == 'cashout') {
                    Cache::store('redis_svr03')->forget($cacheKey);
                    return ['success' => false, 'message' => 'Insufficient Ez credits'];
                }

                $transaction = new Transaction;

                // dd($this->generateCode($request->type));
                $transaction->code = $this->generateCode($request->type);
                $transaction->type = $request->type;
                $transaction->amount = $request->amount;
                $transaction->remaining_credits = \Auth::user()->credits ? \Auth::user()->credits : 0;
                $transaction->user_id = \Auth::user()->id;
                $transaction->status = 'processing';
                // $transaction->voucher_code = $request->voucher_code; //new method no need for voucher code
                $transaction->data = json_encode(['mop' => $request->provider]);
                $isUpdated = $transaction->isDirty();
                // $transaction->save();
                
    //            if($request->provider == 'CoinsPH-payment') {
    //                $coins = \App\Libs\Coins::withOAuthToken('rLcXqwAya29ZTP1blhDIqmqfNIPyMa');
    //                $paymentRequest = $coins->paymentRequest($request->amount, $request->coinsph_user,
    //                        'BC Code: ' . $transaction->code);
    //                if($paymentRequest->success) {
    //                    $reqBody = json_decode($paymentRequest->body);
    //                    $transaction->data = json_encode([
    //                        'mop' => $request->provider,
    //                        'request' => $reqBody->{'payment-request'}
    //                    ]);
    //                    $transaction->save();
    //                } else {
    //                    $transaction->delete();
    //                    return ['success' => false, 'message' => 'Transaction was cancelled due to a Coins.Ph error. Please contact admin.'];
    //                }
    //            }

                if($request->type == 'deposit'){

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

                    foreach(\App\SiteSetting::where('status', 1)->get() as $siteSettings) {
                        if(isset($settings[$siteSettings->name])) {
                            $settings[$siteSettings->name] = $siteSettings->value;
                        }
                    }

                    switch(strtolower($request->provider)){
                        case 'bdo-online':
                        case 'bdo-deposit':
                            $data =  [
                                'account_name' => $settings['bdo-account-name'],
                                'account_number' => $settings['bdo-account-number'],
                                'mop' =>  $request->provider
                            ];
                            break;

                        case 'bpi-online':
                        case 'bpi-deposit':

                            $data =  [
                                'account_name' => $settings['bpi-account-name'],
                                'account_number' => $settings['bpi-account-number'],
                                'mop' =>  $request->provider
                            ];
                            break;

                        case 'securitybank-online':
                        case 'securitybank-deposit':

                            $data =  [
                                'account_name' => $settings['security-account-name'],
                                'account_number' => $settings['security-account-number'],
                                'mop' =>  $request->provider
                            ];
                            break;

                        case 'metrobank-online':
                        case 'metrobank-deposit':

                            $data =  [
                                'account_name' => $settings['metro-account-name'],
                                'account_number' => $settings['metro-account-number'],
                                'mop' =>  $request->provider
                            ];                       
                            break;
                        
                        case 'cebuana-remittance':
                        case 'mlhuiller-remittance':
                        case 'lbc-remittance':
                        case 'palawan-remittance':
                        case 'western-remittance':

                            $data = [
                                'full_name' => $settings['remittance-name'],
                                'mobile_number' => $settings['remittance-number'],
                                'location' => $settings['remittance-location'],
                                'mop' =>  $request->provider
                            ];
                            break;

                        default:
                            $data = ['mop' => $request->provider];
                            break;
                        
                    }

                    $transaction->data = json_encode($data);
                    $transaction->save();
                        
                }
                
                if ($request->type == 'cashout') {# Temporarily close site
                    // return ['success' => false, 'message' => 'We are currently in open beta. You cannot cashout/deposit credits yet. You may register to participate in the open beta and win awesome prizes. For more info, join our 2ez.bet community.  <a href="https://web.facebook.com/groups/2ez.bet">https://web.facebook.com/groups/2ez.bet</a>'];
                    switch ($request->provider_type) {
                        case 'bank':
                            $data = ['account_name' => $request->account_name, 'account_number' => $request->account_number, 'account_type' => $request->account_type, 'account_currency' => $request->account_currency, 'mop' => $request->provider];
                            $transaction->data = json_encode($data);
                            $transaction->save();
                            break;
                        case 'remittance':
                            $data = ['full_name' => $request->full_name, 'mobile_number' => $request->mobile_number, 'location' => $request->location, 'mop' => $request->provider];
                            $transaction->data = json_encode($data);
                            $transaction->save();
                            break;
                    }
                    // $donation = new Donation;
                    // $donation->transaction_id = $transaction->id;
                    // $donation->user_id = \Auth::user()->id;
                    // $donation->amount = $request->donation;
                    // $donation->save();
                    $user = \Auth::user();
                    $user->decrement('credits', $transaction->amount);
                    $user->save();
                    $transaction->save();                    
                    // Fee
                    // $fee = new Fee;
                    // $fee->meta_key = 'cashout';
                    // $fee->meta_value = $transaction->id;
                    // $fee->percent = '0.05';
                    // $fee->collected = $request->donation;
                    // $fee->save();
                }






                // if ($request->voucher_code && !empty($voucherOwner)) {
                //     $commission = new Commission;
                //     $commission->transaction_id = $transaction->id;
                //     $commission->amount = $transaction->amount * ($voucherOwner->voucher_percent/100);
                //     $commission->belongs_to = \App\User::where('voucher_code', $request->voucher_code)->first()->id;
                //     $commission->save();
                // }

                Cache::store('redis_svr03')->forget($cacheKey);

                

                return [
                        'success' => true, 
                        'transaction' => [
                            'code' => $transaction->code,
                            'amount' => $transaction->amount,
                            'id' => $transaction->id,
                            'data' => $transaction->data,
                            'deposit_count' => $completedDepositsCount
                        ]
                ];
            } else {
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



    public function uploadPicture(Request $request)
    {
        $transaction = Transaction::where('id', $request->id)
                ->where('user_id', \Auth::user()->id)->first();
        // dd($request->all());
        $rules = [
            'photo' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) {     
            $thumb = "";
            if ($request->photo) {

                $name = $transaction->code.'-'. time() . '.jpg';
                $base64 = substr($request->photo, strpos($request->photo,',')+1);

                // This saves the base64encoded destinationPath
                file_put_contents(storage_path() . '/uploads/' . $name, base64_decode($base64));

                $file = storage_path() . '/uploads/' . $name;
                $thumb = 'thumb_' . $name;

                $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);

                $transaction->picture = '/uploads/' . $name;
            }
            $transaction->save();
            return ['success' => true];
        }else{
            return ['success' => false,'errors' => $validator->errors()];
        }
        
    }

    public function getAllTransaction(Request $request)
    {
        if (\Auth::user()->isAdmin()) {
            $where = [
                'type' => $request->type
            ];
            $status = !empty($request->status) ? $request->status : '';

            switch($status){
                case 'processing': 
                case 'completed': 
                case 'rejected': 
                    $where['status'] = $status;
                    break;
                default: 
                    break;
            }
            $transactions = Transaction::currentYearUp()->where($where)->with('user','processBy','donation','notes','discrepancy.processBy');
        }else{
            $transactions = \Auth::user()->transactions->where('type',$request->type)->load('donation','notes','discrepancy');
        }
        return \Datatables::of($transactions)->make(true);
    }

    public function profileTransaction(Request $request)
    {
        $transactions = \Auth::user()->transactions()->where('type',$request->type)->with('donation','notes','discrepancy');
        return \Datatables::of($transactions)->make(true);
    }

    public function profileCommissions(Request $request)
    {
        $commissions = Commission::all()->load('transaction.user','transaction.discrepancy')->where('belongs_to',\Auth::user()->id)->where('transaction.status','completed');
        return \Datatables::of($commissions)->make(true);
    }

    public function profileCommissionsPartners(Request $request)
    {
        $commissionsPartners = CommissionsPartner::where('belongs_to',\Auth::user()->id)->get()->load('transaction.user')->where('transaction.status',1);
        return \Datatables::of($commissionsPartners)->make(true);
    }
    
    public function profileCommissionsBets(Request $request)
    {
        $where = [
            'belongs_to' => \Auth::user()->id,
            'type' => 'own'
        ];
        $commissionsBet = CommissionsBets::where($where);
        return \Datatables::of($commissionsBet)->make(true);        
    }

    public function profileSubStreamersTotalCommissionsBets(Request $request)
    {
        $where = [
            'belongs_to' => \Auth::user()->id,
            'type' => 'from-sub',
            'status' => 0,
        ];

        $commissionsBet = CommissionsBets::where($where)
                            ->selectRaw('SUM(amount) as totalAmount, sub_id')
                            ->with('subOwner')
                            ->groupBy('sub_id')->get();
                        
                            // ->get()->with('subOwner:name,voucher_code');
        return \Datatables::of($commissionsBet)->make(true);        
    }

    public function depositCommissions(User $user)
    {
        $data = \Auth::user()->deposit_commissions()->with(['transaction','transaction.user','transaction.discrepancy']);
        $data->whereHas(
            'transaction',function($q){
                $q->where('status','completed');
        });
        return \Datatables::of($data)->make(true);
    }

    public function getAllDonations(Request $request)
    {
        if (\Auth::user()->isAdmin()) {

            // $donations = Donation::all()->load('user','transaction')->where('transaction.status','completed');
            $donations = Donation::with(
                [
                    'user.name',
                    'transaction' => function($query){
                        return $query->where('status','completed');
                     }
                 ]);

            return \Datatables::of($donations)
                                ->make(true);

        }
        return abort(403);
       
    }

    public function getAllCommissions(Request $request)
    {
        if (\Auth::user()->isAdmin()) {
            // $commissions = Commission::all()->load('user','transaction.user','transaction.discrepancy')->where('transaction.status','completed');
            // $commissions = Commission::with(['user','transaction' => function($query){
            //     $query->with('user','discrepancy');
            //     $query->where('status','completed');
            // }]);

           // $commissions = Commission::all()->load('user','transaction.user','transaction.discrepancy')->where('transaction.status','completed');
            $commissions = Commission::with('transaction');

            return \Datatables::of($commissions)
                                ->addColumn('user.name', function($data){
                                    return  $data->user ? $data->user->name : null;
                                })
                                ->addColumn('transaction.user.name', function($data){
                                    return  $data->transaction && $data->transaction->user ? $data->transaction->user->name : null;
                                })
                                ->addColumn('transaction.discrepancy', function($data){
                                    return  $data->transaction && $data->transaction->discrepancy ? $data->transaction->discrepancy : null;
                                })
                                ->filter(function ($query) use ($request) {


                                    $query->whereHas('transaction', function($q) use ($request) {
                                        $q->where('status','completed');
                                    });

                                                    
                                    if(!empty($request->search['value'])){
                                        $searchValue = $request->search['value'];

                                        $query->orWhereHas('user', function($q) use ($searchValue){
                                            $q->where('name','like', "%{$searchValue}%");
                                        });

                                        switch( strtolower($searchValue)){
                                            case 'paid':
                                                $query->orWhere('status',1);
                                                break;
                                            case 'unpaid': 
                                                $query->orWhere('status',0);
                                                break;
                                        }
                                    }
    
                                }, true)                                    
                                ->make(true);
        }
        return abort(403);
    }

    public function getAllCommissionsPartners(Request $request)
    {
        if (\Auth::user()->isAdmin()) {
            // $commissionsPartners = CommissionsPartner::all()->load('transaction.user')->where('transaction.status',1);
            // return \Datatables::of($commissionsPartners)->make(true);

            // $commissionsPartners = CommissionsPartner::with( ['transaction' => function($query){
            //     return $query->where('status',1);
            // }]);

            $commissionsPartners = CommissionsPartner::query();

            return \Datatables::of($commissionsPartners)
                                ->addColumn('user.name', function($data){
                                    return  $data->user ? $data->user->name : null;
                                })
                                ->addColumn('transaction.code', function($data){
                                    return  $data->transaction ? $data->transaction->code : null;
                                })
                                ->addColumn('transaction.amount', function($data){
                                    return  $data->transaction ? $data->transaction->amount : null;
                                })
                                ->addColumn('transaction.user.name', function($data){
                                    return  $data->transaction && $data->transaction->user ? $data->transaction->user->name : null;
                                })
                                ->addColumn('transaction.discrepancy', function($data){
                                    return  $data->transaction && $data->transaction->discrepancy ? $data->transaction->discrepancy : null;
                                })
                                ->filter(function ($query) use ($request) {


         
                                    if(!empty($request->search['value'])){
                                        $searchValue = $request->search['value'];

                                        $query->orWhereHas('user', function($q) use ($searchValue){
                                            $q->where('name','like', "%{$searchValue}%");
                                        });

                                        switch( strtolower($searchValue)){
                                            case 'paid':
                                                $query->orWhere('status',1);
                                                break;
                                            case 'unpaid': 
                                                $query->orWhere('status',0);
                                                break;
                                        }

                                        $query->orWhereHas('transaction', function($q) use ($searchValue) {
                                            $q->where('partner_transactions.code','like','%'.$searchValue.'%');
                                        });
                                    }

                                    $query->whereHas('transaction', function($q) use ($request) {
                                        $q->where('partner_transactions.status',1);
                                    });
    
                                }, true)                                    
                                ->make(true);        }
       
    }

    public function getAllCommissionsBets(Request $request)
    {
        if (\Auth::user()->isAdmin()) {
            // $commissionsPartners = CommissionsPartner::all()->load('transaction.user')->where('transaction.status',1);
            // return \Datatables::of($commissionsPartners)->make(true);

            // $commissionsPartners = CommissionsPartner::with( ['transaction' => function($query){
            //     return $query->where('status',1);
            // }]);

            $commissionsBets = CommissionsBets::with('user');

            return \Datatables::of($commissionsBets)->make(true);
        }
    }

    public function getAllReferalsProfile(Request $request)
    {
        $referals = \Auth::user()->referals()->verified()->with('referedUser.transactions');
        return \Datatables::of($referals)->make(true);
    }
    
    public function getUserRewards(Request $request)
    {
        $rewards = \Auth::user()->rewards;
        return \Datatables::of($rewards)->make(true);
    }

    public function getUserGiftCodes(Request $request)
    {
        $giftCodes = \Auth::user()->giftCodes()->claimables()->get();
        return \Datatables::of($giftCodes)->make(true);
    }
    
    private function generateCode($type) 
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
		    $code = $type_code.'-'.$pass;
    	}while (!Transaction::where('code',$code)->get()->isEmpty());

	    return $code; 

	} 

    public function getReports()
    {

        $all_transactions = Transaction::all()->load('user')->where('user.type','!=','admin');
        $total_amount = $all_transactions->map(function($transaction,$key){
            return $transaction->amount;
        });
        $completed_transactions = Transaction::all()->load('user')->where('user.type','!=','admin')->where('status','completed');
        $total_completed_amount = $completed_transactions->map(function($transaction,$key){
            return $transaction->amount;
        });
        return ['total_amount' => $total_amount,'total_completed_amount' => $total_completed_amount];
    }

	public function unitTest()
	{
        if (\Auth::user()->isAdmin()) {
            $bets = \App\Bet::all()->load('user','match')->whereIn('match_id',[52,53,54]);
            foreach ($bets as $bet) {
                if ($bet->team_id == $bet->match->team_a) {
                    $bet->ratio = $bet->match->team_a_ratio;
                    // $bet->save();
                }
                if ($bet->team_id == $bet->match->team_b) {
                    $bet->ratio = $bet->match->team_b_ratio;
                    // $bet->save();
                }
                if($bet->team_id == $bet->match->team_winner){
                    $ratio = $bet->ratio - 1;
                    $bet->gains = $bet->amount * $ratio;
                    // $bet->save();
                }else{
                    $bet->gains = $bet->amount * -1;
                }
                    $bet->save();

            }
            $table = '';
            $table .= '<table>';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th>ID</th>';
            $table .= '<th>Name</th>';
            $table .= '<th>Amount</th>';
            $table .= '<th>match_id</th>';
            $table .= '<th>team_id</th>';
            $table .= '<th>ratio</th>';
            $table .= '<th>gains</th>';
            $table .= '<th>team a</th>';
            $table .= '<th>team a ratio</th>';
            $table .= '<th>team b</th>';
            $table .= '<th>team b ratio</th>';
            $table .= '<th>team winner</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            foreach ($bets as $bet) {
                $table .= '<tr>';
                $table .= '<td>'.$bet->user->id.'</td>';
                $table .= '<td>'.$bet->user->name.'</td>';
                $table .= '<td>'.$bet->amount.'</td>';
                $table .= '<td>'.$bet->match_id.'</td>';
                $table .= '<td>'.$bet->team_id.'</td>';
                $table .= '<td>'.$bet->ratio.'</td>';
                $table .= '<td>'.$bet->gains.'</td>';
                $table .= '<td>'.$bet->match->team_a.'</td>';
                $table .= '<td>'.$bet->match->team_a_ratio.'</td>';
                $table .= '<td>'.$bet->match->team_b.'</td>';
                $table .= '<td>'.$bet->match->team_b_ratio.'</td>';
                $table .= '<td>'.$bet->match->team_winner.'</td>';
                $table .= '</tr>';
            }
            $table .= '</tbody>';
            $table .= '</table>';

            return $table;
             // $depsosits = Transaction::all()->where('type','deposit')->where('picture',null);
             // $depsosits->map(function($deposit,$key){
             //    $ctr = 0;
             //    if ($deposit->created_at->diffInHours() >= 48) {
             //        echo 'delete <br>';
             //    }
             //    $ctr++;
             // });
            $bets = \App\Bet::matches()->get()->load('match')->whereNotIn('match.status',['settled','draw'])->first();
            dd(\App\Match::find(1)->load('bets.user'));
            return '<img src="'.str_replace('public', 'storage', asset('/uploads/')).'/7SxOREkV8sZXPNS84kCF1500409970.jpg" />';
            echo $this->generateCode('deposit');
        }else{
            return redirect('/');
        }
	}

    public function getBalance()
    {
        if (\Auth::user()->isAdmin()) {
            $users = \App\User::all()->load('transactions.discrepancy','bets');
            $table = '';
            $table .= '<table>';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th>ID</th>';
            $table .= '<th>Name</th>';
            $table .= '<th>Total Approved Deposit</th>';
            $table .= '<th>Total Cashout</th>';
            $table .= '<th>Total Gain</th>';
            $table .= '<th>Tournament Bet</th>';
            $table .= '<th>Available Credits</th>';
            $table .= '<th>Audited Credits</th>';
            $table .= '<th>Difference</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            foreach ($users as $user) {
                $table .= '<tr>';
                $table .= '<td>'.$user->id.'</td>';
                $table .= '<td>'.$user->name.'</td>';
                $table .= '<td>'.$this->totalDeposit($user).'</td>';
                $table .= '<td>'.$this->totalCashout($user).'</td>';
                $table .= '<td>'.$this->totaGain($user).'</td>';
                $table .= '<td>'.$this->tournamenBet($user).'</td>';
                $table .= '<td>'.$user->credits.'</td>';
                $table .= '<td>'.$this->auditCredits($user).'</td>';
                $table .= '<td>'.($this->auditCredits($user)- $user->credits).'</td>';
                $table .= '</tr>';
                $user->credits = $this->auditCredits($user);
                $user->save();
            }
            $table .= '</tbody>';
            $table .= '</table>';

            return $table;

        }else{
            return redirect('/');
        }
    }

    public function getDoubleBets()
    {
        if (\Auth::user()->isAdmin()) {
            $bets = \App\Bet::all()->load('user','match');
            foreach ($bets as $bet) {
                if (condition) {
                    # code...
                }
            }
            $table = '';
            $table .= '<table>';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th>ID</th>';
            $table .= '<th>Name</th>';
            $table .= '<th>Total Approved Deposit</th>';
            $table .= '<th>Total Cashout</th>';
            $table .= '<th>Total Gain</th>';
            $table .= '<th>Tournament Bet</th>';
            $table .= '<th>Available Credits</th>';
            $table .= '<th>Audited Credits</th>';
            $table .= '<th>Difference</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            foreach ($users as $user) {
                $table .= '<tr>';
                $table .= '<td>'.$user->id.'</td>';
                $table .= '<td>'.$user->name.'</td>';
                $table .= '<td>'.$this->totalDeposit($user).'</td>';
                $table .= '<td>'.$this->totalCashout($user).'</td>';
                $table .= '<td>'.$this->totaGain($user).'</td>';
                $table .= '<td>'.$this->tournamenBet($user).'</td>';
                $table .= '<td>'.$user->credits.'</td>';
                $table .= '<td>'.$this->auditCredits($user).'</td>';
                $table .= '<td>'.($this->auditCredits($user)- $user->credits).'</td>';
                $table .= '</tr>';
            }
            $table .= '</tbody>';
            $table .= '</table>';

            return $table;

        }else{
            return redirect('/');
        }
    }

    private function auditCredits($user)
    {
        $total_bets = 0;$total_approved_deposits=0;$total_cashout=0;$audit=0; $total_tournament_bets=0;

        foreach ($user->bets as $bet) {
            $total_bets += $bet->gains;
        }

        foreach ($user->transactions as $transaction) {
            if ($transaction->status == 'completed' && $transaction->type == 'deposit') {
                if ($transaction->discrepancy->count() > 0) {
                    if ($transaction->discrepancy->last()->amount != null) {
                        $total_approved_deposits += $transaction->discrepancy->last()->amount;
                    }else{
                        $total_approved_deposits += $transaction->amount;
                    }
                }else{
                    $total_approved_deposits += $transaction->amount;
                }
            }
        }

        foreach ($user->transactions as $transaction) {
            if ($transaction->type == 'cashout') {
                $total_cashout += $transaction->amount;
            }
        }

        foreach ($user->bets as $bet) {
            if ($bet->type == 'tournament') {
                $total_tournament_bets += $bet->amount;
            }
        }

        $audit = ($total_bets + $total_approved_deposits) - ($total_cashout+$total_tournament_bets);
        return $audit;
    }

    private function totalCashout($user)
    {
       $total_cashout=0;

        foreach ($user->transactions as $transaction) {
            if ($transaction->type == 'cashout') {
                $total_cashout += $transaction->amount;
            }
        }
        return $total_cashout;
    }

    private function totalDeposit($user)
    {
       $total_approved_deposits=0;

        foreach ($user->transactions as $transaction) {
            if ($transaction->status == 'completed' && $transaction->type == 'deposit') {
                if ($transaction->discrepancy->count() > 0) {
                    if ($transaction->discrepancy->last()->amount != null) {
                        $total_approved_deposits += $transaction->discrepancy->last()->amount;
                    }else{
                        $total_approved_deposits += $transaction->amount;
                    }
                }else{
                    $total_approved_deposits += $transaction->amount;
                }
            }
        }

        return $total_approved_deposits;
    }

    private function totaGain($user)
    {
       $total_bets = 0;
        
        foreach ($user->bets as $bet) {
            $total_bets += $bet->gains;
        }

        return $total_bets;
    }

    private function tournamenBet($user)
    {
        $total_tournament_bets=0;
        foreach ($user->bets as $bet) {
            if ($bet->type == 'tournament') {
                $total_tournament_bets += $bet->amount;
            }
        }
        return $total_tournament_bets;
    }
    
    /**
     * Add report for users that saw some bugs
     * 
     * @param Illuminite\Http\Request $request
     * @return Response $response
     */
    public function addReportedBug(Request $request) 
    {
        // return ['success' => true,'message' => 'Bug successfully reported'];
        $rules = [
            'picture' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'subject' => 'required',
            'comment' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) {

            $input = $request->all();
            if ($request->picture) {
                $input['picture'] = \Auth::user()->id . '_' . time() . '.' . $request->picture->getClientOriginalExtension();
                $request->picture->move(storage_path('uploads'), $input['picture']);
            }
            
            $bug = new ReportedBug;
            if($request->picture)
                $bug->picture = $input['picture'];
            $bug->user_id = \Auth::user()->id;
            $bug->proccessed_by = 0;
            $bug->subject = $request->subject;
            $bug->comment = $request->comment;
            $bug->save();
            return  response()->json(['success' => true,'message' => 'Bug successfully reported']);
        }else{
            return  response()->json(['success' => false,'error' => $validator->errors()]);
        }

    }
    
    public function showBugImage(ReportedBug $bug)
    {
        if($bug->exists) {
            $img = \Image::make(storage_path('uploads') . '/' . $bug->picture);
            return $img->response();
        }
    }

    /**
     * Add promotion 
     * 
     * @param Illuminite\Http\Request $request
     * @return Response $response
     */
    public function addPromotion(Request $request) 
    {
        return  response()->json(['success' => false,'message' => 'Promotion is now disable']);
        $promotion = new Promotion;
        $rules = [
            'link' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()) {
            if(\Auth::user()->promotions()->whereDate('created_at','=',date('Y-m-d'))->count() == 0){
                $promotion->link = $request->link;
                $promotion->user_id = \Auth::user()->id;
                $promotion->proccessed_by = 0;
                $promotion->comment = $request->comment;
                $promotion->save();
                return  response()->json(['success' => true,'message' => 'Promotion successfully added']);
            }else{
                return  response()->json(['success' => false,'message' => 'You can only add promotions one day at a time']);
            }
        }else{
            return  response()->json(['error' => $validator->errors()]);
        }
    }

    /**
     * Get all reported bugs of a user or all if admin
     * 
     * @param Illuminate\Http\Request
     * @return Datatables $bugs
     */
    public function getAllReportedBugs(Request $request)
    {
        if ($request->type == 'admin' && \Auth::user()->isAdmin()) {
            $bugs = ReportedBug::all()->load('user', 'processBy', 'thread', 'thread.commentedBy');
            foreach($bugs as $i => $bug) {
                $bugs[$i]->pending_bugs = $bugs->where('status', 0)->count();
            }
        }else{
            $bugs = \Auth::user()->reportedBugs->load('user', 'processBy', 'thread', 'thread.commentedBy');
        }
        return \Datatables::of($bugs)->make(true);
    }

    /**
     * Get all promotion of a user or all if admin
     * 
     * @param Illuminate\Http\Request
     * @return Datatables $promo
     */
    public function getAllPromo(Request $request)
    {
        if ($request->type == 'admin' && \Auth::user()->isAdmin()) {
            // $promos = Promotion::all()->load('user','processBy');
            $promos = Promotion::with('user:name','processBy')->get();
            if($promos->count() > 0 ){
                $promos[0]->pending_promos = $promos->where('status',0)->count();
            }
            // foreach($promos as $i => $promo) {
            //     $promos[$i]->pending_promos = $promo->where('status', 0)->count();
            // }
        }else{
           // $promos = \Auth::user()->promotions->load('user','processBy');
            $promos = \Auth::user()->promotions;
        }
        return \Datatables::of($promos)->make(true);
    }

    /**
     * Get user rebates
     * 
     * @param Illuminate\Http\Request
     * @return Datatables $promo
     */
    public function getRebates()
    {
        $rebates = \Auth::user()->rebates;
        return \Datatables::of($rebates)->make(true);
    }

    /**
     * Transfer wallets
     * 
     * @param Illuminate\Http\Request
     * @return Datatables $promo
     */
    public function transferWallet(Request $request)
    {
        $user = \Auth::user();
        // if($user->verif_level != 'verified'){
        //     \Auth::user()->activeWallet()->update(['transfered' => false]);
        //     return ['error' => false,'message' => 'You must verify your account before you can transfer your wallet to credits!'];
        // }
        // else{
        $total = \Auth::user()->totalActiveWallet();
        $user->credits += $total;
        $user->save();
        \Auth::user()->activeWallet()->update(['transfered' => true]);
        return ['success' => true,'message' => 'Wallet successfully transfered to credits'];
        // }
    }

    public function checkVoucherCodeViaDirect(Request $request)
    {
        $response =   [
            'success' => true, 
            'fail_type' => 0,
            'message' => 'Voucher code exists.'
        ];

        return json_encode($response);

        //new method dont need to check anymore

        // $voucher_code = $request->input('voucher_code');
        // $voucherOwner = \App\User::whereRaw("voucher_code = '{$voucher_code}'")->first();

        // $response =   [
        //     'success' => true, 
        //     'fail_type' => 0,
        //     'message' => 'Voucher code exists.'
        // ];

        // if(empty($voucherOwner)){
        //     $response = [
        //         'success' => false, 
        //         'fail_type' => 1,
        //         'message' => 'Voucher code does not exists.'
        //     ];
        // }else{
        //     //$partners = \App\Partner::select('id', 'partner_name', 'province_id', 'address')->where('active', 1)->get()->load('province', 'profile', 'affliates.user');
            
        //     $parnterJoint = \App\PartnersJoint::where('streamer_user_id', $voucherOwner->id)->get();
        //     if(!empty($parnterJoint)){
        //         $partnersAssociatedWith = \App\Partner::whereIn('id', $parnterJoint->pluck('partner_id')->toArray())->get();


        //         if($partnersAssociatedWith->isNotEmpty()){
        //             $response = [
        //                 'success' => false,
        //                 'fail_type' => 2,
        //                 'message' => 'This voucher code can only be used when Purchasing via Partner.',
        //                 'partners' => $partnersAssociatedWith
        //             ];
        //         }

        //     }else{

        //     }
            

        // }

        // return json_encode($response);
    }

    public function myPurchase($type = "direct", $purchase_id = 0) {
        if($type == "direct") {
            $purchase = Transaction::where(
                [
                    'user_id' => \Auth::user()->id,
                    'id'      => $purchase_id
                ]
            )->first();
        } else {
            $purchase = \App\PartnerTransaction::where(
                [
                    'user_id' => \Auth::user()->id,
                    'id'      => $purchase_id
                ]
            )->first();
        }

        if(!empty($purchase)) {
            $purchase->deposit_type = $type;
        }

        return view(
            'user.myPurchase',
            compact('purchase')
        );
    }

    public function myPurchaseChange(Request $request, $purchase_type = 'direct', $transaction_id = 0) {
        $this->validate($request, [
            'id'      => 'bail|required',
            'receipt' => 'bail|required|image|mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $receipt     = $request->receipt;

        if($purchase_type == 'direct') {
            $transaction = Transaction::where('id', $request->id)
                ->where('user_id', \Auth::user()->id)->first();
        }

        if($purchase_type == 'partner') {
            $transaction = \App\PartnerTransaction::where('id', $request->id)
                ->where('user_id', \Auth::user()->id)->first();
        }

        if(!$transaction) {
            return response(['transaction id' => ['Transaction not found.']], 422);
        }

        $name   = $transaction->code.'-'. time() . '.' . $receipt->getClientOriginalExtension();
        $base64 = substr($request->receipt, strpos($request->receipt,','));

        $request->receipt->move(storage_path() . '/uploads/', $name);

        $file  = storage_path() . '/uploads/' . $name;
        $thumb = 'thumb_' . $name;
        $image = Image::make($file)->encode($receipt->getClientOriginalExtension())->orientate()->fit(200)->save(storage_path() . '/uploads/' . $thumb);

        $transaction->picture = '/uploads/' . $name;

        try{
            $transaction->save();
        } catch(Exception $e) {
            return response(['transaction' => ['Error occured while uploading image.']], 422);
        }

        return response(
            [
                'success' => true,
                'message' => 'Successfully uploaded receipt.'
            ],
            200
        );
    }
}
