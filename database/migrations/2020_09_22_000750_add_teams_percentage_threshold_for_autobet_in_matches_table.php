<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamsPercentageThresholdForAutobetInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->decimal('team_a_threshold_percent')->after('team_b_total_bets')->default(0);
            $table->decimal('team_b_threshold_percent')->after('team_a_threshold_percent')->default(0);
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
                'team_a_threshold_percent',
                'team_b_threshold_percent'
            ]);
        });
    }
}
