<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVoucherCodeColumnToUsersAndDonations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('voucher_code')->nullable()->after('credits');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('voucher_code')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('voucher_code');
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('voucher_code');
        });
    }
}
