<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        if (App::environment('local')) {
            $day = date("d");

            for($x = 1; $x <= $day+100; $x++){
                $testUser = User::create([
                        'name' => 'Test User ' . $x,
                        'email' => 'testuser'.$x.'@2ez.bet',
                        'password' => bcrypt('password101'),
                        'credits' => 1000000,
                        'provider' => 'local',
                        'provider_id' => '',
                        'avatar' => 'images/default_avatar.png',
                        'verified' => 1,
                        'accept_tos' => 1
                    ]);
                    
                    // VerifyUser::create([
                    //     'user_id' => $testUser->id,
                    //     'token' => str_random(40)
                    // ]);

                    $this->command->line('Created Test User with id:' . $testUser->id . ' / email: ' . $testUser->email . " password: password101");
            }
        }

  
    

 
    }
}
