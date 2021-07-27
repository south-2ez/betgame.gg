<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersVerifiedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_verified', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('verified_type',255)->default('2ez'); //2ez or partner
            $table->integer('verified_by')->default(0); //if 2ez 0 then if partner: id of partner
            $table->integer('verified_by_user_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('verified_type');
            $table->index('verified_by');
            $table->index('verified_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_verified');
    }
}
