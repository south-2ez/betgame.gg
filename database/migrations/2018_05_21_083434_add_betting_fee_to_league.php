<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBettingFeeToLeague extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leagues', function (Blueprint $table) {
            $table->decimal('betting_fee', 5, 3)->default(0.000)->after('league_winner');
            $table->decimal('favorites_minimum', 5, 2)->default(0.00)->after('betting_fee');
        });
        
        Schema::table('league_team', function (Blueprint $table) {
            $table->boolean('is_favorite')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leagues', function (Blueprint $table) {
            $table->dropColumn('betting_fee');
            $table->dropColumn('favorites_minimum');
        });
        
        Schema::table('league_team', function (Blueprint $table) {
            $table->dropColumn('is_favorite');
        });
    }
}
