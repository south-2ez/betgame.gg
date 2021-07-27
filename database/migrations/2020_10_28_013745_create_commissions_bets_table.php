<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionsBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions_bets', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('match_id');
            $table->dateTime('date_settled')->nullable();
            $table->decimal('amount', 30,2)->default(0);
            $table->decimal('percentage',10,2)->default(0);
            $table->integer('belongs_to');
            $table->longText('user_bets')->nullable(); // (which will contain the amount & earnings on each bet) 
            $table->integer('status')->default(0); //0 means unpaid, 1 means paid
            $table->timestamps();
            $table->softDeletes();

            //indices
            $table->index('match_id');
            $table->index('belongs_to');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commissions_bets');
    }
}
