<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_threads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reported_bug_id');
            $table->integer('user_id');
            $table->text('comment');
            $table->timestamps();
        });

        Schema::table('reported_bugs', function (Blueprint $table) {
            $table->string('subject', 100)->after('picture');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_threads');
        Schema::table('reported_bugs', function (Blueprint $table) {
            $table->dropColumn('subject');
        });
    }
}
