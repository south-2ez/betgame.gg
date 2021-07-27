<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatioToMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->string('team_a_ratio')->nullable()->after('team_a');
            $table->string('team_b_ratio')->nullable()->after('team_b');
        });
        
        Schema::table('bets', function (Blueprint $table) {
            $table->string('gains')->nullable()->after('amount');
            $table->string('ratio')->nullable()->after('gains');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('credits')->default(0)->change();
        });
        
        $matches = \App\Match::where('status', '!=', 'open')->get();
        
        foreach($matches as $match) {
            $match->team_a_ratio = $match->teamA->matchRatio($match->id);
            $match->team_b_ratio = $match->teamB->matchRatio($match->id);
            
            foreach ($match->bets as $bet) {
                $bet->ratio = $bet->team_id == $match->team_a ?
                        $match->team_a_ratio : $match->team_b_ratio;
                if ($match->status == 'draw')
                    $bet->gains = 0;
                else
                    $bet->gains = $bet->team_id == $match->team_winner ?
                            ($bet->amount * $bet->ratio) - $bet->amount : -($bet->amount);
                $bet->save();
            }
            $match->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('team_a_ratio');
            $table->dropColumn('team_b_ratio');
        });
        
        Schema::table('bets', function (Blueprint $table) {
            $table->dropColumn('gains');
            $table->dropColumn('ratio');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->integer('credits')->default(0)->change();
        });
    }
}
