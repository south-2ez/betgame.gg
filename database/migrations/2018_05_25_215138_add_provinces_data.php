<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProvincesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('provinces')->insert([
            ['province' => 'Alcantara'],
            ['province' => 'Alcoy'],
            ['province' => 'Alegria'],
            ['province' => 'Aloguinsan'],
            ['province' => 'Argao'],
            ['province' => 'Asturias'],
            ['province' => 'Badian'],
            ['province' => 'Balamban'],
            ['province' => 'Bantayan'],
            ['province' => 'Barili'],
            ['province' => 'Basak'],
            ['province' => 'Bogo'],
            ['province' => 'Boljoon'],
            ['province' => 'Borbon'],
            ['province' => 'Bulacao'],
            ['province' => 'Carcar'],
            ['province' => 'Carmen'],
            ['province' => 'Catmon'],
            ['province' => 'Cebu City'],
            ['province' => 'Compostela'],
            ['province' => 'Consolacion'],
            ['province' => 'Cordova'],
            ['province' => 'Daanbantayan'],
            ['province' => 'Dalaguete'],
            ['province' => 'Danao'],
            ['province' => 'Dumanjug'],
            ['province' => 'Ginatilan'],
            ['province' => 'Lapu-Lapu'],
            ['province' => 'Liloan'],
            ['province' => 'Madridejos'],
            ['province' => 'Malabuyoc'],
            ['province' => 'Mandaue'],
            ['province' => 'Medellin'],
            ['province' => 'Minglanilla'],
            ['province' => 'Moalboal'],
            ['province' => 'Naga'],
            ['province' => 'Oslob'],
            ['province' => 'Pilar'],
            ['province' => 'Pinamungajan'],
            ['province' => 'Poro'],
            ['province' => 'Ronda'],
            ['province' => 'Samboan'],
            ['province' => 'San Fernando'],
            ['province' => 'San Francisco'],
            ['province' => 'San Remegio'],
            ['province' => 'Santa Fe'],
            ['province' => 'Santander'],
            ['province' => 'Sogod'],
            ['province' => 'Tabogon'],
            ['province' => 'Tabuelan'],
            ['province' => 'Tabunok'],
            ['province' => 'Talisay'],
            ['province' => 'Tuburan'],
            ['province' => 'Tudela'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('provinces')->truncate();
    }
}
