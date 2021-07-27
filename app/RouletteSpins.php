<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RouletteSpins extends Model
{
    use SoftDeletes;

    protected $table = 'roulette_spins';

    // protected $creditTimeRangesIndex = [
    //         4 => [ 'min' => 4077, 'max' => 4083, 'limit' => 53, 'value' => 100 ], //100
    //         1 => [ 'min' => 4097, 'max' => 4103, 'limit' => 5, 'value' => 1000 ], //1000
    //         16 => [ 'min' => 4117, 'max' => 4123, 'limit' => 53, 'value' => 100 ], //100
    //         13 => [ 'min' => 4137, 'max' => 4143, 'limit' => 10, 'value' => 500 ], //500
    //         10 => [ 'min' => 4157, 'max' => 4163, 'limit' => 53, 'value' => 100 ], //100
    //         7 => [ 'min' => 4177, 'max' => 4183, 'limit' => 20, 'value' => 200], //200            
    //     ];

    // protected $noWinTimeRangesIndex = [
    //         0 => [ 'min' => 4104, 'max' => 4116, 'limit' => 99999, 'value' => 0 ], //0 & 17
    //         2 => [ 'min' => 4084, 'max' => 4096, 'limit' => 99999, 'value' => 0 ],  //2 & 3
    //         5 => [ 'min' => 4064, 'max' => 4076, 'limit' => 99999, 'value' => 0 ], //5 & 6
    //         8 => [ 'min' => 4164, 'max' => 4176, 'limit' => 99999, 'value' => 0 ],  //8 & 9
    //         11 => [ 'min' => 4156, 'max' => 4144, 'limit' => 99999, 'value' => 0 ], //11 & 12
    //         14 => [ 'min' => 4136, 'max' => 4124, 'limit' => 99999, 'value' => 0 ], //14 & 15     
    // ];

    //0 = 100
    //1 & 2 = bokya
    //3 = 200
    //4 & 5 = bokya
    //6 = 500
    //7 & 8 = bokya
    // 9 = jacket
    //10 & 11 = bokya
    //12 = 1000
    // 13 & 14 = bokya
    //15 = 100
    //16 & 17 = bokya
    //18 = 200
    //19 & 20 = bokya
    //21 = 100
    //22 & 23  = bokya
    //24 = 100
    //25 & 26 //bokya

    protected $creditTimeRangesIndex = [
            0 => [ 'min' => 4077, 'max' => 4083, 'limit' => 70, 'value' => 100, 'stopAt' => 8 ], //100
            3 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 200, 'stopAt' => 48 ], //200
            6 => [ 'min' => 4117, 'max' => 4123, 'limit' => 10, 'value' => 500, 'stopAt' => 88 ], //500
            9 => [ 'min' => 4137, 'max' => 4143, 'limit' => 4, 'value' => 'jacket', 'stopAt' => 128 ], //jacket 
            12 => [ 'min' => 4157, 'max' => 4163, 'limit' => 4, 'value' => 1000, 'stopAt' => 168 ], //1000 ...
            15 => [ 'min' => 4177, 'max' => 4183, 'limit' => 70, 'value' => 100, 'stopAt' => 208 ], //100    
            18 => [ 'min' => 4177, 'max' => 4183, 'limit' => 25, 'value' => 200, 'stopAt' => 248], //200    
            21 => [ 'min' => 4177, 'max' => 4183, 'limit' => 70, 'value' => 100, 'stopAt' => 288], //100  
            24 => [ 'min' => 4177, 'max' => 4183, 'limit' => 70, 'value' => 100, 'stopAt' => 328], //100 
        ];

    protected $noWinTimeRangesIndex = [  
        // 1,2,4,5,7,8,10,11,13,14,16,17,19,20,22,23,25,26
        1 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 22 ], //0, +14
        2 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 34 ], //0, +12

        4 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 62 ], //0, +14
        5 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 74 ], //0, + 12

        7 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 102 ], //0, +14
        8 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 114 ], //0, +12

        10 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 142 ], //0, +14
        11 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 154 ], //0, +12     
        
        13 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 182 ], //0, +14
        14 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 194 ], //0, +12         

        16 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 222 ], //0, +14
        17 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 234 ], //0, +12 
        
        19 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 262 ], //0, +14
        20 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 274 ], //0, +12     
        
        19 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 302 ], //0, +14
        20 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 314 ], //0, +12  
        
        19 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 342 ], //0, +14
        20 => [ 'min' => 4097, 'max' => 4103, 'limit' => 25, 'value' => 0, 'stopAt' => 354 ], //0, +12         
    ];    

    protected $flipPossibleWinnings = [
        100,
        100,
        200,
        500,
        1000
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeToday($query)
    {
        $today = date('Y-m-d');
        return $query->whereDate('created_at',$today);
    }

    public function scopeCreditsWon($query, $value = 100){
        return $query->where('credits_won',$value);
    }

    public function prepareSpin()
    {

        \DB::setDefaultConnection('mysql_transaction');
        $startAngle = env('ROULETTE_SPIN_START_ANGLE',360);
        $returnSpinTime = false;
        $calculateSpinRange = [];
        $creditTimeRangesIndexMinMax = [];

        $possibleWinnings = $this->possibleWinnings();
        $winningKey = !empty($possibleWinnings) ? array_rand($possibleWinnings, 1) : -1;

        if($winningKey >= 0){
            $creditTimeRangesIndexMinMax = [
                'min' => $this->creditTimeRangesIndex[$winningKey]['min'],
                'max' => $this->creditTimeRangesIndex[$winningKey]['max'],
                'winningKey' => $winningKey,
                'stopAt' =>  $this->creditTimeRangesIndex[$winningKey]['stopAt'],
                'value' =>  $this->creditTimeRangesIndex[$winningKey]['value'],
            ];
        }

        return [
            'index' => $winningKey,
            'segment' => $this->calculateSpinTime($creditTimeRangesIndexMinMax),
        ];

        // $prevSpinTime = 0;

        while(!$returnSpinTime){


            $spinTime = $this->calculateSpinTime($creditTimeRangesIndexMinMax);
            $hitIndex = $winningKey;
            $prevSpinTime = $spinTime;

            if($hitIndex > 0){
                $aboutToWinData = $this->creditTimeRangesIndex[$hitIndex];
                $aboutToWinCredits = $aboutToWinData['value'];

                if(in_array($aboutToWinCredits, $possibleWinnings)){

                    $limitWinners = $aboutToWinData['limit'];
                    $currentWinnersCount = \App\RouletteSpins::today()
                                                        ->creditsWon($aboutToWinCredits)
                                                        ->count();
                            
                    if($currentWinnersCount >= $limitWinners){
                        $spinTime = $this->calculateSpinTime([]);
                    }
                    $returnSpinTime = true;

                }

            }else{
                $returnSpinTime = true;
            }
        }
        
        return [
            'start_angle' => $startAngle,
            'spin_time' => $spinTime,
        ];
    }

    public function calculateSpinTime($minMax = [])
    {

        if(!empty($minMax)){
            //return rand( $minMax['min'], $minMax['max'] );
            return  $minMax['stopAt'];
        }else{
            // $useKey = array_rand($this->noWinTimeRangesIndex,1);
            // $min = $this->noWinTimeRangesIndex[$useKey]['min'];
            // $max = $this->noWinTimeRangesIndex[$useKey]['max'];
            // return rand($min,$max);
            //return rand(4077,4183);
            $useKey = array_rand($this->noWinTimeRangesIndex,1);
            return  $this->noWinTimeRangesIndex[$useKey]['stopAt'];       
        }

    }

    public function getPredictedCreditsWon($spinTime = 0)
    {

        $hit = false;
        $hitIndex = 0;
        
        if($spinTime > 0){
            foreach($this->creditTimeRangesIndex as $key => $range){
                $min = $range['min'];
                $max = $range['max'];
                if( $spinTime >= $min && $spinTime <= $max){
                    
                    $hit = true;
                    $hitIndex = $key;

                    break;
                }
            }
        }

        return $hitIndex;
    }

    public function possibleWinnings()
    {
        $possibleWinnings = [];
   
        foreach($this->creditTimeRangesIndex as $key => $range){
            $include = false;
            switch($range['value']){
                case 1000 :
                case 'jacket':

                    // $hitNumber = rand(1,650);
                    // if($hitNumber == rand(1,650)){
                    //     $include = true;
                    // }

                    //higher chance
                    $hitNumber = rand(1,325);
                    if($hitNumber == rand(1,325)){
                        $include = true;
                    }
                break;

                case 500: 

                    // $hitNumber = rand(1,250);
                    // if($hitNumber == rand(1,250)){
                    //     $include = true;
                    // }
                    $hitNumber = rand(1,125);
                    if($hitNumber == rand(1,125)){
                        $include = true;
                    }                    

                break;
                case 200: 

                    // $hitNumber = rand(1,120);
                    // if($hitNumber == rand(1,120)){
                    //     $include = true;
                    // }

                    $hitNumber = rand(1,60);
                    if($hitNumber == rand(1,60)){
                        $include = true;
                    }

                break;
                case 100: 

                    // $hitNumber = rand(1,70);
                    // if($hitNumber == rand(1,70)){
                    //     $include = true;
                    // }

                    $hitNumber = rand(1,20);
                    if($hitNumber == rand(1,20)){
                        $include = true;
                    }
                break; 

                default: 
                    $include = false;
                break;
            }

            if($include){
                $possibleWinnings[$key] = $range['value'];
            }

        }

        return $possibleWinnings;

    }

    public function prepareFlip($selectedCardIndex)
    {

        \DB::setDefaultConnection('mysql_transaction');
        $creditTimeRangesIndexMinMax = [];

        $possibleWinnings = $this->possibleWinningsFlip();

        return [
            'index' => $selectedCardIndex,
            'cardValues' => $this->calculateFlipIndexes($possibleWinnings,$selectedCardIndex),
        ];

 
    }

    public function possibleWinningsFlip()
    {
        $possibleWinnings = [];
   
        foreach($this->creditTimeRangesIndex as $key => $range){
            $include = false;
            switch($range['value']){
                case 1000:

                    $hitNumber = rand(1,650);
                    if($hitNumber == rand(1,650)){
                        $include = true;
                    }

                    //higher chance
                    // $hitNumber = rand(1,325);
                    // if($hitNumber == rand(1,325)){
                    //     $include = true;
                    // }
                break;

                case 500: 

                    $hitNumber = rand(1,250);
                    if($hitNumber == rand(1,250)){
                        $include = true;
                    }
                    // $hitNumber = rand(1,125);
                    // if($hitNumber == rand(1,125)){
                    //     $include = true;
                    // }                    

                break;
                case 200: 

                    $hitNumber = rand(1,120);
                    if($hitNumber == rand(1,120)){
                        $include = true;
                    }

                    // $hitNumber = rand(1,60);
                    // if($hitNumber == rand(1,60)){
                    //     $include = true;
                    // }

                break;
                case 100: 

                    $hitNumber = rand(1,70);
                    if($hitNumber == rand(1,70)){
                        $include = true;
                    }

                    // $hitNumber = rand(1,20);
                    // if($hitNumber == rand(1,20)){
                    //     $include = true;
                    // }
                break; 

                default: 
                    $include = false;
                break;
            }

            if($include){
                $possibleWinnings[$key] = $range['value'];
            }

        }

        return $possibleWinnings;

    }  
    
    
    public function calculateFlipIndexes($winnings, $selectedCardIndex)
    {
   
        $winningKey = !empty($winnings) ? array_rand($winnings, 1) : -1;
        $cardIndexes = [];
        $cardValueCounter = [
            0 => 0,
            100 => 0,
            200 => 0,
            500 => 0,
            1000 => 0,
        ];
    
        if($winningKey >= 0){
            $winCredits =  $winnings[$winningKey];
            $cardIndexes[$selectedCardIndex] = $winCredits;
            $cardValueCounter[$winCredits]++;
        }else{
            $cardIndexes[$selectedCardIndex] = 0;
            $cardValueCounter[0]++;
            // $cardIndexes[$selectedCardIndex] = 1000;
            // $cardValueCounter[1000]++;
        }

        // echo " ---- selectedCardIndex : ----" . $selectedCardIndex ."--- " . $winningKey.  "--- <br/>";

        for($x = 0; $x < 16; $x++){
            if(!isset($cardIndexes[$x])){
                $setCredits = array_rand($cardValueCounter);

                switch($setCredits){
                    case 100: 
                        $cardValueCounter[100]++;
                        if($cardValueCounter[100] > 2){
                            $cardIndexes[$x] = 0;
                        }else{
                            $cardIndexes[$x] = 100;
                        }
                        break;
                    case 200: 
                        $cardValueCounter[200]++;
                        if($cardValueCounter[200] > 1){
                            $cardIndexes[$x] = 0;
                        }else{
                            $cardIndexes[$x] = 200;
                            
                        }
                        break;
                    case 500: 
                        $cardValueCounter[500]++;
                        if($cardValueCounter[500] > 1){
                            $cardIndexes[$x] = 0;
                        }else{
                            $cardIndexes[$x] = 500;
                            
                        }
                        break;
                    case 1000:
                        $cardValueCounter[1000]++;
                        if($cardValueCounter[1000] > 1){
                            $cardIndexes[$x] = 0;
                        }else{
                            $cardIndexes[$x] = 1000;
                        }                        
                        break;
                    case 0: 
                    default: 
                        $cardValueCounter[0]++;
                        $cardIndexes[$x] = 0;
                    break;
                    
                }

              
            }
        }

        ksort($cardIndexes);
        return $cardIndexes;
    }

}
