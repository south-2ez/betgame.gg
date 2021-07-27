<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\VerifyUser;
use App\Mail\VerifyMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Referal;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }
    
    protected function registered(\Illuminate\Http\Request $request, $user)
    {
        if ($request->ajax()) {
            auth()->logout();
            return ['warning' => 'Account successfully created.'];
        } else {
            $this->guard()->logout();
            return redirect('/login')->with('status', 'Account successfully created.');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'                => 'required|string|max:255',
            'email'               => 'required|string|email|not_throw_away|max:255|unique:users',
            'password'            => 'required|string|min:6|confirmed',
            'redeem_voucher_code' => 'nullable|string|max:100|exists:users,voucher_code'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'                => $data['name'],
            'email'               => $data['email'],
            'credits'             => 0,
            'password'            => bcrypt($data['password']),
            'provider'            => 'local',
            'provider_id'         => '',
            'avatar'              => 'images/default_avatar.png',
            'verified'            => 1,
            'redeem_voucher_code' => (!empty($data['redeem_voucher_code'])) ? $data['redeem_voucher_code'] : null
        ]);
        
        // $verifyUser = VerifyUser::create([
        //     'user_id' => $user->id,
        //     'token' => str_random(40)
        // ]);
 
        //\Mail::to($user->email)->send(new VerifyMail($user));
        
        return $user;
    }
    
    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                #do smoething for user with referal
                $referal = Referal::where(['refered_user' => $user->id, 'verified' => 0])->first();
                if($referal){
                    $ref_owner = $referal->owner;
                    // $ref_owner->credits += 100.00; # Remove for real money betting
                    // $ref_owner->save();
                    $referal->verified = 1 ;
                    $referal->save();
                
                    // \App\Reward::create([
                    //     'user_id' => $ref_owner->id,
                    //     'type' => 'referral',
                    //     'class_id' => $referal->id,
                    //     'description' => 'Awarded Referral: ' . $referal->code . ' with ' . $referal->fee . ' credits',
                    //     'credits' => $referal->fee
                    // ]); # Remove for real money betting
                }
                $status = "Your e-mail is verified. You can now login.";
            }else{
                $status = "Your e-mail is already verified. You can now login.";
            }
        }else{
            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }
 
        return redirect('/login')->with('status', $status);
    }
}
