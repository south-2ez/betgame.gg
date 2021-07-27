<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecimalPrecisionCirculatingPointsMatchReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->decimal('circulating_credits_after_settled', 30,2)->change();
            $table->decimal('circulating_credits_before_settled', 30,2)->change();
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
            $table->decimal('circulating_credits_after_settled', 10,2)->change();
            $table->decimal('circulating_credits_before_settled', 10,2)->change();
        });
    }
}
