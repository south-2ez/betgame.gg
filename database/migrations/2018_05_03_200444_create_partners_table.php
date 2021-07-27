<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_name', 191);
            $table->integer('user_id');
            $table->string('outstanding_credits', 191);
            $table->string('address', 191)->nullable();
            $table->timestamps();
        });

        Schema::create('partner_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 191);
            $table->string('type', 191);
            $table->string('trade_type', 191);
            $table->integer('partner_id');
            $table->integer('user_id');
            $table->string('voucher_code', 191)->nullable();
            $table->string('remaining_credits', 191);
            $table->string('amount', 191);
            $table->string('new_credits', 191)->nullable();
            $table->string('status', 191);
            $table->string('picture', 191)->nullable();
            $table->integer('process_by')->nullable();
            $table->text('data')->nullable();
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
        Schema::dropIfExists('partners');
        Schema::dropIfExists('partner_transactions');
    }
}
