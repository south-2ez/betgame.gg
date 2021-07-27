<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBetTypeInCommissionsBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions_bets', function (Blueprint $table) {
            $table->integer('match_id')->nullable()->change();
            $table->integer('league_id')->nullable()->after('match_id');
            $table->string('bet_type',255)->default('match')->after('league_id');
         
            $table->index('league_id');
            $table->index('bet_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions_bets', function (Blueprint $table) {
            $table->dropColumn(['league_id','bet_type']);
        });
    }
}
