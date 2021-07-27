<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionsViaPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions_partners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_transaction_id');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->integer('belongs_to');
            $table->boolean('status')->default(false);
            $table->index('partner_transaction_id');
            $table->index('belongs_to');
            $table->index('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commissions_partners');
    }
}
