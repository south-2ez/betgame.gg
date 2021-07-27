<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcessedFieldInUpdatedMatchOddsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('updated_match_odds_logs', function (Blueprint $table) {
            $table->integer('processed')->default(0)->after('message');
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
            $table->dropColumn('processed');
        });
    }
}
