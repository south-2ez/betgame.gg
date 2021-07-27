<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetbotLessAdjustments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betbot_less_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bet_id')->default(0);
            $table->decimal('amount',20,2)->default(0);
            $table->timestamps();
            $table->index('bet_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('betbot_less_adjustments');
    }
}
