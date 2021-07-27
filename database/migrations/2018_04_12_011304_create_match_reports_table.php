<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_reports', function (Blueprint $table) {
            $table->integer('id');
            $table->primary('id');
            $table->integer('settled_by');
            $table->text('data');
            $table->decimal('total_match_bet', 10, 2)->default(0.00);
            $table->decimal('match_fee', 5, 3)->default(0.000);
            $table->decimal('total_fees_collected', 10, 2)->default(0.00);
            $table->decimal('total_payout', 10, 2)->default(0.00);
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
        Schema::dropIfExists('match_reports');
    }
}
