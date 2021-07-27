<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportedBugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_bugs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('picture')->nullable();
            $table->string('comment')->nullable();
            $table->smallInteger('status')->default(0);
            $table->integer('proccessed_by');
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
        Schema::dropIfExists('reported_bugs');
    }
}
