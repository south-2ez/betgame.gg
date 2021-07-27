<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Mail\VerifyMail;
use App\VerifyUser;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    protected function authenticated(\Illuminate\Http\Request $request, $user) 
    {
        if ($request->ajax()) {
            if (!$user->verified) {
                $this->resendVerification($user);
                auth()->logout();
                return ['warning' => 'You need to confirm your account. We have sent you an activation code, please check your email.'];
            }
            return response()->json([
                        'auth' => auth()->check(),
                        'user' => $user,
                        'intended' => $this->redirectPath(),
            ]);
        } else {
            if (!$user->verified) {
                $this->resendVerification($user);
                auth()->logout();
                return back()->with('warning', 'You need to confirm your account. We have sent you an activation code, please check your email.');
            }
            return redirect()->intended($this->redirectPath());
        }
    }

    public function redirectToProvider($provider)
    {
        $vcode            = (request('vcode')) ? request('vcode') : '';
        $explode_prev_url = explode('/',url()->previous());

        if(in_array('referral',$explode_prev_url)){
            // request()->session()->flash('referral_code',end($explode_prev_url));
            request()->session()->put('referral_code',end($explode_prev_url));
        }

        //for redeem voucher code
        if(!empty($vcode)) {
            request()->session()->put('redeem_voucher_code', $vcode);
        }

        return Socialite::driver($provider)->fields([
                'name','first_name', 'last_name', 'email', 
            ])->redirect();
    }
    
    public function handleProviderCallback($provider)
    {
        // Redirect user to home page when app was declined.
        if (!request()->has('code') || request()->has('denied')) {
            return redirect('/');
        }

        $socialite = Socialite::driver($provider)->fields([
            'name','first_name', 'last_name', 'email'
        ]);


        try {
            // $socialite = Socialite::driver($provider)->user();
            $socialite = Socialite::driver($provider)->fields([
                'name','first_name', 'last_name', 'email'
            ]);

        } catch (InvalidStateException $e) {
            $socialite = Socialite::driver($provider)->fields([
                'name','first_name', 'last_name', 'email'
            ])->stateless();
        }
        
        $user      = $socialite->user();
        $authUser  = $this->findOrCreateUser($user, $provider);

        \Auth::login($authUser, true);
        return redirect($this->redirectTo);
        

    }
    
    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = \App\User::where('provider_id', $user->id)->first();

        if ($authUser) {
            return $authUser;
        }
        return \App\User::create([
            'name'                => $user->name ? $user->name : $user->getNickname(),
            'email'               => $user->getEmail(),
            'provider'            => $provider,
            'provider_id'         => $user->id,
            'avatar'              => $user->getAvatar(),
            'redeem_voucher_code' => (!empty(request()->session()->has('redeem_voucher_code'))) ? request()->session()->pull('redeem_voucher_code') : null
        ]);
    }
    
    private function resendVerification($user)
    {
        if (VerifyUser::where('user_id', $user->id)->count() == 0) {
            $verifyUser = VerifyUser::create([
                        'user_id' => $user->id,
                        'token' => str_random(40)
            ]);

            \Mail::to($user->email)->send(new VerifyMail($user));
        }
    }
}
