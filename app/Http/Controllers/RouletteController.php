<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Cache;
use App\RouletteSpins;

class RouletteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [] ]);
    }

    public function validateSpin(Request $request)
    {
        $user = \Auth::user();
        // $spinsToday = $user->spins()->where('event_code','HLLWN202xxxx0')->today()->get();
        $spinsToday = $user->spins()->where('event_code','XMAS2020')->today()->get();

        $response = [
            'success' => false,
            'message' => 'Try again tomorrow. You can only spin once a day.'            
        ];

        if($spinsToday->isEmpty() && $user->credits >= 100){
            
            $today = date('Y-m-d');
            $date_until = new Carbon('2021-01-04');
            $date_today = new Carbon($today);

            if($date_until <= $date_today){
                return [
                    'success' => false,
                    'message' => 'Can not spin anymore. Christmas 2020 event ended.'
                ];
            }
            
            $spin = new RouletteSpins; 
            $data = $spin->prepareSpin();
            $spinCacheKey = 'roullete_spin_data_'. $today .'_'. getUserCacheKey($user);

            Cache::store('redis_svr03')->put($spinCacheKey, $data, 15);

            $response = [
                'success' => true,
                'message' => 'Spinning allowed',
                'data' => $data,
            ];

        }else if($user->credits < 100){
            $response = [
                'success' => false,
                'message' => 'You need to have atleast 100 credits to spin.',
            ];

        }

        return $response;
    }

    public function preSaveSpinCheck(Request $request){

        $user = \Auth::user();
        $user_id = $user->id;

        $spinsToday = $user->spins()->where('event_code','XMAS2020')->today()->get();

        if($spinsToday->isNotEmpty()){
            $response = [
                'success' => false,
                'message' => 'Warning: This might get you banned from 2ez.bet. You can only flip once a day. Kung Hei Fat Choi! :)'            
            ];    
        }else{
            $response = [
                'success' => true,
                'message' => 'Allowed to flip.',            
            ];
            
        } 
        
        return $response;

    }

    public function saveSpin(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'endAngle' => 'required',
        ]);

        if(!$validator->passes()){
            return [ 
                    'success' => false, 
                    'errors' => $validator->errors(),
                    'message' => 'Error.'
                    ];
        }

        $user = \Auth::user();
        $user_id = $user->id;
        $event_name = '2EZ.BET Christmas Event - 2020';
        $event_code = 'XMAS2020';
        $credits_won = $request->credits_won;
        $given_to = 'User #:' . $user->id . ' | ' . $user->name;
        $description = $event_name;

        $spinsToday = $user->spins()->where('event_code','XMAS2020')->today()->get();



        if($spinsToday->isNotEmpty()){
            $response = [
                'success' => false,
                'message' => 'ERROR (DAILY_SPIN_LIMIT_REACHED): Warning: 2ez.bet ADMIN notified. You can only spin once a day.'            
            ];    
            
            $spin = new RouletteSpins; 
            $spin->user_id = $user_id;
            $spin->event_name = $event_name;
            
            $spin->event_code = $event_code;

            $spin->save();

            return $response;
        }else{
            $spin = new RouletteSpins; 
            $spin->user_id = $user_id;
            $spin->event_name = $event_name;
            
            $spin->event_code = $event_code;

            $spin->save();
        }      


    
        $today = date('Y-m-d');
    
        $spinCacheKey = 'roullete_spin_data_'. $today .'_'. getUserCacheKey($user);



        if(!Cache::store('redis_svr03')->has($spinCacheKey)){
            return [ 
                    'success' => false, 
                    'message' => 'Error NOT_SET. Please refresh browser.',
                    ];
        }else{

            $spinDataInCache = Cache::store('redis_svr03')->pull($spinCacheKey);
            //data that we sent first to frontend; 
            //then we get it back 
            //when saving to validate if data isnt manipulated
            $validate_data = [
                'index' => $request->validData['index'],
                'segment' => $request->validData['segment'],
            ];        

            $invalid_data = $spinDataInCache != $validate_data;

            if($invalid_data){
                return [ 
                    'success' => false, 
                    'message' => 'Error INVALID_DATA.Please refresh browser. ',
                ];    
            }


            $gc_token = str_random(10);
            $gc_code = "GC-". $event_code .'-'. strtoupper($gc_token);
            $gc_code_id = 0;

            
            $spinned_credits_won = 0;
            switch( strtolower($credits_won)){
                case '100 credits': 
                    $spinned_credits_won = 100;
                    break;
                case '200 credits': 
                    $spinned_credits_won = 200;
                    break;
                case '500 credits':
                    $spinned_credits_won = 500; 
                    break;
                case '1000 credits':
                    $spinned_credits_won = 1000;
                    break; 
                case '2ez jacket': 
                    $spinned_credits_won = '2ez jacket';
                    break;                                       
                case 'bokya':
                default:
                    $spinned_credits_won = 0;
                break;
            }

            $spin->credits_won = $spinned_credits_won;

            if(!empty($spinned_credits_won)){

                // $spinCacheKey = 'roullete_spin_data_'. $today .'_'. getUserCacheKey($user);
                // $spinDataInCache = Cache::get($spinCacheKey);

                // dd($spinDataInCache,)

                if($spinned_credits_won  == '2ez jacket'){
                    $gc_code = "GC-". $event_code .'-JCKT'. strtoupper($gc_token);

                    $gc = \App\GiftCode::create([
                        'code' => $gc_code,
                        'amount' => 0,
                        'purpose' => 1,
                        'give_to' => $given_to,
                        'status' => 2,
                        'description' => $description,
                        'generated_by' => 0
                    ]);   
                }else{
                    $gc = \App\GiftCode::create([
                        'code' => $gc_code,
                        'amount' => $spinned_credits_won,
                        'purpose' => 1,
                        'give_to' => $given_to,
                        'status' => 2,
                        'description' => $description,
                        'generated_by' => 0
                    ]);   
                }

                
                $gc_code_id = $gc->id;
            }

            $spin->gift_code_id = $gc_code_id;
            $spin->save();

            return json_encode([
                'success' => true,
                'won' => $gc_code_id >  0, 
                'credits_won' => $credits_won,
                'gc' => $gc_code_id > 0 ? $gc->code : 'N/A',
                'message' => 'Won ' .$credits_won
            ]);

        }


    }

    public function validateFlip(Request $request)
    {
        $user = \Auth::user();
        $spinsToday = $user->spins()->where('event_code','EASTEREGG2021')->today()->get();

        $response = [
            'success' => false,
            'message' => 'Try again tomorrow. You can only OPEN/CRACK an egg once a day.'            
        ];

        if($spinsToday->isEmpty() && $user->credits >= 100){
            
            $today = date('Y-m-d');
            $date_until = new Carbon('2021-04-12');
            $date_today = new Carbon($today);

            if($date_until <= $date_today){
                return [
                    'success' => false,
                    'message' => 'Can not FLIP anymore. Chinese New Year 2021 event ended.'
                ];
            }

            $spinCacheKey = 'roullete_flip_data_'. $today .'_'. getUserCacheKey($user);
            $cacheData = $this->generateRandomString();

            Cache::store('redis_svr03')->put($spinCacheKey, $cacheData, 15);

            $response = [
                'success' => true,
                'message' => 'Flipping allowed',
                // 'data' => $data,
            ];

        }else if($user->credits < 100){
            $response = [
                'success' => false,
                'message' => 'You need to have atleast 100 credits to FLIP.',
            ];

        }

        return $response;
    }


    public function saveFlip(Request $request)
    {

        $cardIndexValues = [];
        $validator = \Validator::make($request->all(), [
            'cardIndex' => 'required',
        ]);

        if(!$validator->passes()){
            return [ 
                    'success' => false, 
                    'errors' => $validator->errors(),
                    'message' => 'Error.'
                    ];
        }

        $user = \Auth::user();
        $user_id = $user->id;
        $event_name = '2EZ.BET Easter Egg Event - 2021';
        $event_code = 'EASTEREGG2021';

        $spin = new RouletteSpins; 
        $cardIndexes = $spin->prepareFlip($request->cardIndex);
        $cardIndexValues = $cardIndexes['cardValues'];
     
        $credits_won = $cardIndexValues[$request->cardIndex];

        // dd($cardIndexes, $request->cardIndex);

        $given_to = 'User #:' . $user->id . ' | ' . $user->name;
        $description = $event_name;

        $spinsToday = $user->spins()->where('event_code','EASTEREGG2021')->today()->get();

        if($spinsToday->isNotEmpty()){
            $response = [
                'success' => false,
                'message' => 'ERROR (DAILY_CRACK_LIMIT_REACHED): Warning: 2ez.bet ADMIN notified. You can only crack an egg once a day.'            
            ];    
            
            $spin = new RouletteSpins; 
            $spin->user_id = $user_id;
            $spin->event_name = $event_name;
            
            $spin->event_code = $event_code;

            $spin->save();

            return $response;
        }else{
            $spin = new RouletteSpins; 
            $spin->user_id = $user_id;
            $spin->event_name = $event_name;
            
            $spin->event_code = $event_code;

            $spin->save();
        }      


        $today = date('Y-m-d');
        $spinCacheKey = 'roullete_flip_data_'. $today .'_'. getUserCacheKey($user);


        if(!Cache::store('redis_svr03')->has($spinCacheKey)){
            return [ 
                    'success' => false, 
                    'message' => 'Error NOT_SET. Please refresh browser.',
                    ];
        }else{

            $spinDataInCache = Cache::store('redis_svr03')->pull($spinCacheKey);
            //data that we sent first to frontend; 
            //then we get it back 
            //when saving to validate if data isnt manipulated
            // $validate_data = [
            //     'index' => $request->validData['index'],
            //     'segment' => $request->validData['segment'],
            // ];        

            // $invalid_data = $spinDataInCache != $validate_data;

            // if($invalid_data){
            //     return [ 
            //         'success' => false, 
            //         'message' => 'Error INVALID_DATA.Please refresh browser. ',
            //     ];    
            // }


            $gc_token = str_random(10);
            $gc_code = "GC-". $event_code .'-'. strtoupper($gc_token);
            $gc_code_id = 0;

            
            $spinned_credits_won = 0;
            switch( strtolower($credits_won)){
                case '100 credits': 
                case 100:
                    $spinned_credits_won = 100;
                    break;
                case '200 credits': 
                case 200:
                    $spinned_credits_won = 200;
                    break;
                case '500 credits':
                case 500:
                    $spinned_credits_won = 500; 
                    break;
                case '1000 credits':
                case 1000:
                    $spinned_credits_won = 1000;
                    break; 
                case '2ez jacket': 
                    $spinned_credits_won = '2ez jacket';
                    break;                                       
                case 'bokya':
                default:
                    $spinned_credits_won = 0;
                break;
            }

            $spin->credits_won = $spinned_credits_won;

            if(!empty($spinned_credits_won)){


                if($spinned_credits_won  == '2ez jacket'){
                    $gc_code = "GC-". $event_code .'-JCKT'. strtoupper($gc_token);

                    $gc = \App\GiftCode::create([
                        'code' => $gc_code,
                        'amount' => 0,
                        'purpose' => 1,
                        'give_to' => $given_to,
                        'status' => 2,
                        'description' => $description,
                        'generated_by' => 0
                    ]);   
                }else{
                    $gc = \App\GiftCode::create([
                        'code' => $gc_code,
                        'amount' => $spinned_credits_won,
                        'purpose' => 1,
                        'give_to' => $given_to,
                        'status' => 2,
                        'description' => $description,
                        'generated_by' => 0
                    ]);   
                }

                
                $gc_code_id = $gc->id;
            }

            $spin->gift_code_id = $gc_code_id;
            $spin->save();

            return json_encode([
                'success' => true,
                'won' => $gc_code_id >  0, 
                'credits_won' => $credits_won,
                'gc' => $gc_code_id > 0 ? $gc->code : 'N/A',
                'message' => 'Won ' .$credits_won,
                'cardIndexValues' => $cardIndexValues
            ]);

        }


    }    

    public function preSavFlipCheck(){

        $user = \Auth::user();
        $user_id = $user->id;

        $spinsToday = $user->spins()->where('event_code','EASTEREGG2021')->today()->get();

        if($spinsToday->isNotEmpty()){
            $response = [
                'success' => false,
                'message' => 'Warning: This might get you banned from 2ez.bet. You can only flip once a day. Kung Hei Fat Choi! :)'            
            ];    
        }else{
            $response = [
                'success' => true,
                'message' => 'Allowed to flip.',            
            ];
            
        } 
        
        return $response;

    }

    public function generateRandomString($length = 24) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
 

    public function testSpinTime(){
        $spin = new RouletteSpins; 
        dd($spin->prepareSpin());
    
        echo "<pre>";
        for($x = 0; $x <= 1000; $x++){
        
            // $data =  $spin->calculateSpinTime();
            // $oneKHit = false;
            // $hitIndex = $spin->getPredictedCreditsWon($data);            
            // $hitText = $hitIndex > 0 ? ' - HIT' : ' - ENK';
            // echo $data ." - ". $hitIndex . $hitText. "<br/>";

            echo "<pre>"; 
            $test = $spin->possibleWinnings();
            $random_key = !empty($test) ? array_rand($test, 1) : -1;
            print_r($test);
            echo ' rand : ' . $random_key ?? NULL;
            echo !empty($test) ? ' HIT' : '';
            echo "</pre>";
        }

        
        echo "</pre>"; 

        echo "<br/>";
    }

    public function messageUsersWhoHaventSpin(){

        $currentUser = \Auth::user();
        if($currentUser->isAdmin()){

            $from_user_id = !empty($currentUser) ? $currentUser->id : 0;


            $spinedUsersToday = \App\RouletteSpins::select('user_id')
                                    ->today()
                                    ->get()
                                    ->pluck('user_id');

            $users = \App\User::select('id')
                                ->where('credits','>=',100)
                                ->whereNotIn('id',$spinedUsersToday)
                                ->whereNotIn('type',['admin','matchmanager'])
                                ->get();

            // dd($spinedUsersToday, $users);

            $countSent = 0;
            if($users->isNotEmpty()){
                foreach($users as $user){
                    $message =
                        "Ghostly Greetings, dear bettor!

                        It's Halloween season and we have a spooky surprise for you.

                        Trick or Treat! Test your luck by clicking the pumpkin in our site home page (beside our logo) and get a chance to win up to 1000 2ez credits daily.

                        All you need to have is atleast 100 2ez credits in your account in order to have 1 spin daily.

                        The daily spin resets at 12:00MN.
                        Promo runs from November 1,2020 to November 7, 2020 only!

                        So what are you waiting for? Find the pumpkin and happy spinning!

                        Best of luck!
                        #HappyHalloWin #2ez
                    ";

                    $user_id = $user->id;
                    
                    $newMessage = \App\UserMessages::create([
                        'from_user_id' => $from_user_id,
                        'user_id' => $user_id,
                        'message' => $message
                    ]);

                    if(!empty($newMessage)){
                        $countSent++;
                    }

                }
            }

            echo 'Messages sent: ' . $countSent;


        }else{
            abort(404);
        }
    }



    


}
