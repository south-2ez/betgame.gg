<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerDiscrepanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_discrepancies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_transaction_id');
            $table->integer('user_id');
            $table->integer('partner_id');
            $table->integer('amount')->nullable();
            $table->string('picture', 100)->nullable();
            $table->string('mop', 100)->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('partner_discrepancies');
    }
}
