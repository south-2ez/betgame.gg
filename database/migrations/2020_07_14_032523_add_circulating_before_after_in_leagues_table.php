<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCirculatingBeforeAfterInLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leagues', function (Blueprint $table) {
            $table->longtext('circulating_credits_before_settled')->after('display_order');
            $table->longtext('circulating_credits_after_settled')->after('circulating_credits_before_settled');
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
            $table->dropColumn([
                'circulating_credits_before_settled',
                'circulating_credits_after_settled'
            ]);
        });
    }
}
