<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAverageMatchReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->integer('total_bettors')->after('data');
            $table->decimal('average_match_bet', 10, 2)->default(0.00)->after('total_match_bet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->dropColumn('total_bettors');
            $table->dropColumn('average_match_bet');
        });
    }
}
