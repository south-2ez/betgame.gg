<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitialOddsFieldsInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('is_initial_odds_enabled')->default(0)->after('teamb_score');
            $table->decimal('team_a_initial_odd', 10,2)->default(0)->after('is_initial_odds_enabled');
            $table->decimal('team_b_initial_odd', 10,2)->default(0)->after('team_a_initial_odd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('is_initial_odds_enabled');
            $table->dropColumn('team_a_initial_odd');
            $table->dropColumn('team_b_initial_odd');
        });
    }
}
