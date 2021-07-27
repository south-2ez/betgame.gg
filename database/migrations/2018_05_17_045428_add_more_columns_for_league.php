<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsForLeague extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leagues', function (Blueprint $table) {
            $table->integer('league_winner')->after('image');
            $table->boolean('status')->default(false)->after('league_winner');
            $table->boolean('betting_status')->default(false)->after('status');
        });
        
        Schema::create('league_team', function (Blueprint $table) {
            $table->integer('league_id')->index();
            $table->integer('team_id')->index();
        });
        
        Schema::table('bets', function (Blueprint $table) {
            $table->integer('league_id')->index()->after('type');
        });
        
        \DB::update("UPDATE leagues SET status = 1, league_winner = 2 WHERE id = 1");
        \DB::insert("INSERT INTO league_team SELECT 1, id FROM teams WHERE id < 19");
        \DB::update("UPDATE bets SET league_id = 1 WHERE type = 'tournament'");
        \DB::update("UPDATE bets b JOIN matches m ON (b.match_id = m.id) "
                . "SET b.league_id = m.league_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leagues', function (Blueprint $table) {
            $table->dropColumn('league_winner');
            $table->dropColumn('status');
            $table->dropColumn('betting_status');
        });
        
        Schema::table('bets', function (Blueprint $table) {
            $table->dropColumn('league_id');
        });
        
        Schema::dropIfExists('league_team');
    }
}
