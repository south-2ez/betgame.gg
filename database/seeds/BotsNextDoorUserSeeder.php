<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class BotsNextDoorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkIfExists = User::where('email','serena.bot@2ez.bet')->first();

        //Serena Next Door
        if(empty($checkIfExists)){
            $serenaUser = User::create([
                'name' => 'Serena Next Door',
                'email' => 'serena.bot@2ez.bet',
                'password' => bcrypt('passw0rd2ez'),
                'credits' => 1000000,
                'provider' => 'local',
                'provider_id' => '',
                'avatar' => 'images/default_avatar.png',
            ]);
            
            $verifySerenaUser = VerifyUser::create([
                'user_id' => $serenaUser->id,
                'token' => str_random(40)
            ]);

            $this->command->line('Created & Verified Serena Next Door User with id:' . $serenaUser->id . ' and credits: ' . $serenaUser->credits);
        }else{
            $this->command->line('Serena Next Door User already exist with id:' . $checkIfExists->id . ' and credits: '. $checkIfExists->credits);
        }

        //Tomboy Next Door
        $checkIfExists = User::where('email','tomboy.bot@2ez.bet')->first();
        if(empty($checkIfExists)){
     
            $tomboyUser = User::create([
                'name' => 'Tomboy Next Door',
                'email' => 'tomboy.bot@2ez.bet',
                'password' => bcrypt('passw0rd2ez'),
                'credits' => 1000000,
                'provider' => 'local',
                'provider_id' => '',
                'avatar' => 'images/default_avatar.png',
            ]);
            
            $verifyTomboyUser = VerifyUser::create([
                'user_id' => $tomboyUser->id,
                'token' => str_random(40)
            ]);

            $this->command->line('Created & Verified Tomboy Next Door User with id:' . $tomboyUser->id. ' and credits: ' . $tomboyUser->credits);

        }else{
            $this->command->line('Tomboy Next Door User already exist with id:' . $checkIfExists->id. ' and credits: ' . $checkIfExists->credits);
        }
 
    }
}
