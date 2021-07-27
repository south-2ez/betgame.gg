<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWhyStreamLinksInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            
            
            $table->text('stream_twitch')->nullable()->after('label');
            $table->text('stream_yt')->nullable()->after('stream_twitch');
            $table->text('stream_fb')->nullable()->after('stream_yt');
            $table->text('stream_other')->after('stream_fb')->nullable();
            $table->text('more_info_link')->after('stream_other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'stream_other',
                'more_info_link',
                'stream_twitch',
                'stream_yt',
                'stream_fb'
            ]);
        });
    }
}
