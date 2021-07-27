<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAdminComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reported_bugs', function (Blueprint $table) {
            $table->text('admin_comment')->nullable()->after('comment');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->text('admin_comment')->nullable()->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reported_bugs', function (Blueprint $table) {
            $table->dropColumn('admin_comment');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('admin_comment');
        });
    }
}
