<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamCFieldsInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('team_c')->after('team_b_ratio')->nullable();
            $table->string('team_c_ratio',191)->after('team_c')->nullable();
            $table->string('teamc_score',191)->after('teamb_score')->nullable();
            $table->decimal('team_c_initial_odd', 10,2)->default(0)->after('team_b_initial_odd');
            $table->decimal('team_c_total_bets', 10,2)->default(0)->after('team_b_total_bets');
            $table->decimal('team_c_threshold_percent', 8,2)->default(0)->after('team_b_max_threshold_percent');
            $table->decimal('team_c_max_threshold_percent', 8,2)->default(0)->after('team_c_threshold_percent');
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
            $table->dropColumn('team_c_max_threshold_percent');
            $table->dropColumn('team_c_threshold_percent');
            $table->dropColumn('team_c_total_bets');
            $table->dropColumn('team_c_initial_odd');
            $table->dropColumn('teamc_score');
            $table->dropColumn('team_c_ratio');
            $table->dropColumn('team_c');
        });
    }
}
