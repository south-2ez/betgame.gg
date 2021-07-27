<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeFieldInUpdatedMatchOddsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('updated_match_odds_logs', function (Blueprint $table) {
            $table->string('type',250)->default('odds_update')->after('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('updated_match_odds_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
