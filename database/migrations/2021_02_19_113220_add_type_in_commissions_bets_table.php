<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeInCommissionsBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions_bets', function (Blueprint $table) {
            $table->integer('sub_id')->nullable()->after('status');
            $table->string('type',255)->default('own')->after('sub_id');
            $table->index('type');
            $table->index('sub_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions_bets', function (Blueprint $table) {
            $table->dropColumn(['sub_id','type']);
        });
    }
}
