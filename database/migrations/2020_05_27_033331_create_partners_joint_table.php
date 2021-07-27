<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersJointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners_joint', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id');
            $table->integer('streamer_user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index('partner_id');
            $table->index('streamer_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners_joint');
    }
}
