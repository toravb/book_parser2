<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastParsingFieldInAudioParsingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_parsing_statuses', function (Blueprint $table) {
            $table->timestamp('last_parsing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audio_parsing_statuses', function (Blueprint $table) {
            $table->dropColumn(['last_parsing']);
        });
    }
}
