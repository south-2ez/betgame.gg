<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedRejectedDateInPartnerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->dateTime('approved_rejected_date')->after('process_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->dropColumn('approved_rejected_date');
        });
    }
}
