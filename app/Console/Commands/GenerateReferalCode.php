<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class GenerateReferalCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hermes:generate-referal-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for generating referal code';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $this->info($user->name);
            $user->referal_code = $this->generateCode($user->id);
            $user->save();
            $this->info($user->referal_code);
            
        }
    }

    /**
     * Generate code for referals
     * @param int $user_id
     * @return string code
     */
    private function generateCode($user_id) 
    { 
    	do{
		    $chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz"; 
		    srand((double)microtime()*1000000); 
		    $i = 0; 
		    $pass = '' ; 

		    while ($i <= 7) { 
		        $num = rand() % 33; 
		        $tmp = substr($chars, $num, 1); 
		        $pass = $pass . $tmp; 
		        $i++; 
		    }
		    $code = $pass;
    	}while (!User::find($user_id)->where('referal_code',$code)->get()->isEmpty());

	    return $code; 

	} 
}
