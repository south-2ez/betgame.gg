<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCirculatingCreditsFieldAllowNullableInLeagues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leagues', function (Blueprint $table) {
            $table->longtext('circulating_credits_before_settled')->nullable()->change();
            $table->longtext('circulating_credits_after_settled')->nullable()->change();
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
            //
        });
    }
}
