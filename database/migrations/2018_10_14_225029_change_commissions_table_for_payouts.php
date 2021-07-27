<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCommissionsTableForPayouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('commissions', 'commissions_old');
        Schema::create('commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned();  
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->integer('belongs_to')->unsigned();  
            $table->boolean('status')->default(false);
            $table->index(['transaction_id', 'belongs_to']);
            $table->foreign('belongs_to')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade');
            $table->timestamps();
        });

        $old_commissions = \DB::table('commissions_old')->get();
        foreach ($old_commissions as $commission) {
            if(\App\Transaction::find($commission->transaction_id) && \App\User::find($commission->belongs_to)){
                $comm = new \App\Commission;
                $comm->transaction_id = $commission->transaction_id;
                $comm->amount = \App\Transaction::findOrFail($commission->transaction_id)->amount * (\App\User::findOrFail($commission->belongs_to)->voucher_percent/100);
                $comm->belongs_to = $commission->belongs_to;
                $comm->created_at = $commission->created_at;
                $comm->updated_at = $commission->updated_at;
                $comm->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
