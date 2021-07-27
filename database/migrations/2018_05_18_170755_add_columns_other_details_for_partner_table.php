<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOtherDetailsForPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('partner_credits', 100)->nullable()->change();
            $table->string('mobile_number', 100)->after('partner_receivables')->nullable();
            $table->string('landline_number', 100)->after('mobile_number')->nullable();
            $table->string('contact_person', 100)->after('landline_number')->nullable();
            $table->string('email', 100)->after('contact_person')->nullable();
            $table->string('bpi_account', 100)->after('email')->nullable();
            $table->string('bdo_account', 100)->after('bpi_account')->nullable();
            $table->string('cashout_choice', 100)->after('bdo_account')->nullable();
            $table->string('operation_time', 100)->after('cashout_choice')->nullable();
            $table->string('facebook_link', 191)->after('address')->nullable();
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
            $table->string('partner_credits', 191)->change();
            $table->dropColumn('mobile_number');
            $table->dropColumn('landline_number');
            $table->dropColumn('contact_person');
            $table->dropColumn('email');
            $table->dropColumn('bpi_account');
            $table->dropColumn('bdo_account');
            $table->dropColumn('cashout_choice');
            $table->dropColumn('operation_time');
            $table->dropColumn('facebook_link');
        });
    }
}
