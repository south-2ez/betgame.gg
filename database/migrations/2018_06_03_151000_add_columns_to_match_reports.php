<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMatchReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->decimal('circulating_credits_before_settled', 10, 2)->default(0.00)->after('total_payout');
            $table->decimal('circulating_credits_after_settled', 10, 2)->default(0.00)->after('total_payout');
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
            $table->dropColumn('circulating_credits_before_settled', 10, 2);
            $table->dropColumn('circulating_credits_after_settled', 10, 2);
        });
    }
}
