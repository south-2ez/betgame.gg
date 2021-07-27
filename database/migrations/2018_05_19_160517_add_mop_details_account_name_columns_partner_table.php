<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMopDetailsAccountNameColumnsPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('payment_mode', 191)->after('operation_time')->nullable();
            $table->string('details', 191)->after('payment_mode')->nullable();
            $table->string('bpi_account_name', 191)->after('bpi_account')->nullable();
            $table->string('bdo_account_name', 191)->after('bdo_account')->nullable();
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
            $table->dropColumn('payment_mode');
            $table->dropColumn('details');
            $table->dropColumn('bpi_account_name');
            $table->dropColumn('bdo_account_name');
        });
    }
}
