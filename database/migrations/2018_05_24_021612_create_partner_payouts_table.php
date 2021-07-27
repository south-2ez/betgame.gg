<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_id');
            $table->integer('user_id');
            $table->string('earnings', 100);
            $table->integer('process_by');
            $table->string('data', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_payouts');
    }
}
