<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewDatesFieldsTotalBetsInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dateTime('date_settled')->after('main_match')->nullable();
            $table->dateTime('date_set_live')->after('date_settled')->nullable();
            $table->dateTime('date_reopened')->after('date_set_live')->nullable();
            $table->decimal('team_a_total_bets',30,2)->after('team_b_initial_odd')->default(0);
            $table->decimal('team_b_total_bets',30,2)->after('team_a_total_bets')->default(0);
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
            $table->dropColumn([
                'date_settled',
                'date_set_live',
                'date_reopened',
                'team_a_total_bets',
                'team_b_total_bets',
            ]);
        });
    }
}
