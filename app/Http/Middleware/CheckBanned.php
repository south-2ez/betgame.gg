<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Cache;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd(auth()->user()->banned_until);

        $checkBannedKey = 'checkBannedKeyxxx_' . getUserCacheKey();
        $checkedOn = Cache::get( $checkBannedKey );

        if(empty($checkedOn)){

            
            if(auth()->check() && auth()->user()->id == 588){

                // if(\App::environment('prod')){
                    $currentIp = request()->ip();
                    \Mail::raw('Carlo Chan is using the site | ' . $currentIp, function($message) use($currentIp)  {
                        $message->from('admin@2ez.bet', '2ez.bet Admin');
                        $message->to('aljun.demetria@gmail.com')
                        ->subject('Carlo Chan - ADMIN ACCESS | ' . $currentIp);
                    });
                // }
                
            }

            if (auth()->check() && auth()->user()->banned_until && Carbon::now()->lessThan(auth()->user()->banned_until)) {
                $banned_days = Carbon::now()->diffInDays(auth()->user()->banned_until);
                $remainingText = auth()->user()->banned_until->diffForHumans();
                auth()->logout();
    
                if ($banned_days > 14) {
                    $message = 'Your account has been suspended. Please contact 2ez.bet Customer Service <a class="alert-link" href="https://www.facebook.com/2ez.bet/" target="_blank">here.</a>';
                } else {
                    $message = 'Your account has been suspended for '.$remainingText.'. Please contact 2ez.bet Customer Service <a class="alert-link" href="https://www.facebook.com/2ez.bet/" target="_blank">here.</a>';
                }
    
                session(['message' => $message]);
                return redirect('/login');
    
            }

            Cache::remember( $checkBannedKey, 5 , function (){

                return Carbon::now();
            
            });
        }



        return $next($request);
    }
}
