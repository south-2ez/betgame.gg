<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerSubagentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_subagents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id');
            $table->integer('sub_partner_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index('partner_id');
            $table->index('sub_partner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_subagents');
    }
}
