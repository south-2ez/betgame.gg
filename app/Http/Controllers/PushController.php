<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\PushDemo;
use App\Notifications\PushUserNegativeCredits;
use App\Notifications\PushUserNewDepositViaDirect; 
use App\Notifications\PushUserNewCashoutViaDirect; 
use App\Notifications\PushUserNewReportedBug;
use App\User;
use Auth;
use Notification;

class PushController extends Controller
{
    /**
     * Store the PushSubscription.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $this->validate($request,[
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = Auth::user();
        $user->updatePushSubscription($endpoint, $key, $token);
        
        return response()->json(['success' => true],200);
    }

    /**
     * Send Push Notifications to current user.
     * 
     * @return \Illuminate\Http\Response
     */
    public function push(){
        Notification::send(Auth::user(),new PushDemo);
        return redirect()->back();
    }

    public function pushNegativeCreditsUserAlert($user){
        $admins = \App\User::admins()->get();
        Notification::send($admins,new PushUserNegativeCredits($user));
        return true;
    }

    public function pushNewTransactionViaDirect($transaction){
        $admins = \App\User::admins()->get();
        if($transaction->type == 'deposit'){
            Notification::send($admins,new PushUserNewDepositViaDirect($transaction));
        }else if($transaction->type == 'cashout'){
            Notification::send($admins,new PushUserNewCashoutViaDirect($transaction));
        }

        return true;        
    }

    public function pushNewReportedBug($bug){
        $admins = \App\User::admins()->get();
        Notification::send($admins,new PushUserNewReportedBug($bug));
        return true;        
    }
}