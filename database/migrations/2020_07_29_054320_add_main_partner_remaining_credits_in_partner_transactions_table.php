<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMainPartnerRemainingCreditsInPartnerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->decimal('main_partner_remaining_credits',15,2)->after('main_partner_earnings')->nullable();

            //remaining_credits
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
            $table->dropColumn('main_partner_remaining_credits');
        });
    }
}
