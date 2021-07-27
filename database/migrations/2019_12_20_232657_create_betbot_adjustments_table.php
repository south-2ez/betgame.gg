<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetbotAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betbot_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('match_id')->default(0);
            $table->datetime('match_schedule')->nullable();
            $table->longtext('adjusted_bet_ids')->nullable();
            $table->decimal('adjusted_amount',10,2)->default(0);
            $table->integer('adjusted_at')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['match_id', 'match_schedule', 'adjusted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('betbot_adjustments');
    }
}
