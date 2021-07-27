<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersMopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners_mop', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id');
            $table->string('type',255);
            $table->string('method',1000);
            $table->string('availability',2000)->default('24/7');
            $table->integer('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->index('partner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners_mop');
    }
}
