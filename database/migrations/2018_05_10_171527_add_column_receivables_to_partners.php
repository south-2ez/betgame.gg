<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReceivablesToPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('partner_receivables', 191)->after('partner_earnings')->nullable();
        });
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->string('remaining_credits', 191)->nullable()->change();
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
            $table->dropColumn('partner_receivables');
        });
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->string('remaining_credits', 191)->change();
        });
    }
}
