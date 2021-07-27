<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataColumnTypeFromTextToLongTextMatchReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->longText('new_data')->after('settled_by');
        });

         \DB::statement('update match_reports set new_data = data;');

        Schema::table('match_reports', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->renameColumn('new_data', 'data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->text('new_data')->after('settled_by');
        });

        \DB::statement('update match_reports set new_data = data;');

        Schema::table('match_reports', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->renameColumn('new_data', 'data');
        });
    }
}
