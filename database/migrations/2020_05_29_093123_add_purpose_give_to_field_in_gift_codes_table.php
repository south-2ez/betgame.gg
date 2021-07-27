<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurposeGiveToFieldInGiftCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gift_codes', function (Blueprint $table) {
            $table->integer('purpose')->after('amount')->default(1);
            $table->string('give_to',500)->after('purpose')->nullable();
            $table->index('purpose');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gift_codes', function (Blueprint $table) {
            $table->dropColumns(['purpose','give_to']);
        });
    }
}
