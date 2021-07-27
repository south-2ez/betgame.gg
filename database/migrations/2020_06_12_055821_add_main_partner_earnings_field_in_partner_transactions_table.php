<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMainPartnerEarningsFieldInPartnerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->decimal('main_partner_earnings', 10, 2)->default(0.00)->after('partner_earnings');
            $table->integer('main_partner_id')->nullable()->after('main_partner_earnings');
            $table->index('main_partner_id');
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
            
            $table->dropColumn(['main_partner_earnings', 'main_partner_id']);
        });
    }
}
