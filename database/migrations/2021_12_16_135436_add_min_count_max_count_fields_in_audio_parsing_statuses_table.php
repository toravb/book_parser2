<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinCountMaxCountFieldsInAudioParsingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_parsing_statuses', function (Blueprint $table) {
            $table->unsignedInteger('min_count')->default(0);
            $table->unsignedInteger('max_count')->default(0);
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
            $table->dropColumn(['min_count', 'max_count']);
        });
    }
}
