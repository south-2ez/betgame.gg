<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageAndReceiptColumnsAtPartnerPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->string('receipt', 190)->after('process_by');
            $table->text('message')->after('receipt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->dropColumn('receipt');
            $table->dropColumn('message');
        });
    }
}
