<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositCommisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned();  
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->integer('belongs_to')->unsigned();  
            $table->boolean('status')->default(false);
            $table->index(['transaction_id', 'belongs_to']);
            $table->foreign('belongs_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
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
        Schema::dropIfExists('deposit_commissions');
    }
}
