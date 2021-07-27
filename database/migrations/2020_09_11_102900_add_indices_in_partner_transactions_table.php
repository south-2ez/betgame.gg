<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndicesInPartnerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
                $table->index('type');
                $table->index('trade_type');
                $table->index('partner_id');
                $table->index('user_id');
                $table->index('status');
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
            $table->dropIndex(['type', 'trade_type', 'partner_id', 'user_id', 'status']);
        });
    }
}
