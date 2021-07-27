<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('league_id');
            $table->smallInteger('best_of');
            $table->datetime('schedule');
            $table->integer('team_a');
            $table->integer('team_b');
            $table->integer('team_winner')->nullable();
            $table->decimal('fee', 2, 2)->default(0.00);
            $table->string('status')->default('open');
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
        Schema::dropIfExists('matches');
    }
}
