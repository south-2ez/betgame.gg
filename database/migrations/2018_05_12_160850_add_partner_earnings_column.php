<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartnerEarningsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->string('partner_earnings', 191)->after('new_credits')->nullable();
            $table->text('partner_comment')->after('partner_earnings')->nullable();
            $table->smallInteger('status')->change();
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
            $table->dropColumn('partner_earnings');
            $table->dropColumn('partner_comment');
            $table->string('status', 191)->change();
        });
    }
}
