<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcceptTosUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('accept_tos')->default(false)->after('verified');
        });
        
        Schema::table('leagues', function (Blueprint $table) {
            $table->string('type')->default('dota2')->after('id');
            $table->boolean('expired')->default(false)->after('betting_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('accept_tos');
        });
        
        Schema::table('leagues', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('expired');
        });
    }
}
