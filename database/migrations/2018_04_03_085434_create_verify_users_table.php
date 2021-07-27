<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verify_users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('token');
            $table->timestamps();
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('verified')->after('avatar')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verify_users');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verified');
        });
    }
}
