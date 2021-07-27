<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouletteSpinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roulette_spins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('event_code',255)->default('SPIN_EVENT');
            $table->string('event_name',1000)->default('Free spins');
            $table->integer('gift_code_id')->default(0);
            $table->integer('credits_won')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('gift_code_id');
            $table->index('event_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roulette_spins');
    }
}
