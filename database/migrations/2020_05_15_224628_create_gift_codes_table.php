<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->decimal('amount',30,2)->default(0);
            $table->longtext('description')->nullable();
            $table->integer('generated_by')->default(0); //0 system generated
            $table->integer('user_id')->nullable();
            $table->integer('status')->default(0);
            $table->dateTime('date_redeemed')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_codes');
    }
}
