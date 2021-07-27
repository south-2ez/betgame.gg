<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePartnerCreditsToDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->decimal('partner_credits', 15, 2)->default(0.00)->change();
            $table->decimal('partner_earnings', 15, 2)->default(0.00)->change();
            $table->dropColumn('partner_receivables');
        });

        Schema::table('partner_discrepancies', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0.00)->change();
        });

        Schema::table('partner_donations', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0.00)->change();
        });

        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->decimal('earnings', 10, 2)->default(0.00)->change();
        });

        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->decimal('remaining_credits', 15, 2)->default(0.00)->change();
            $table->decimal('amount', 15, 2)->default(0.00)->change();
            $table->decimal('new_credits', 15, 2)->default(0.00)->change();
            $table->decimal('partner_earnings', 15, 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('partner_credits', 191)->nullable()->change();
            $table->string('partner_earnings', 191)->nullable()->change();
            $table->decimal('partner_receivables', 15, 2)->default(0.00);
        });

        Schema::table('partner_discrepancies', function (Blueprint $table) {
            $table->integer('amount')->change();
        });

        Schema::table('partner_donations', function (Blueprint $table) {
            $table->string('amount', 180)->change();
        });

        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->string('earnings', 100)->change();
        });

        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->string('remaining_credits', 191)->nullable()->change();
            $table->string('amount', 191)->change();
            $table->string('new_credits', 191)->nullable()->change();
            $table->string('partner_earnings', 191)->nullable()->change();
        });
    }
}
