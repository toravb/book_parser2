<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationFileSizeColumnsToAudioAudiobooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_audiobooks', function (Blueprint $table) {
            $table->unsignedInteger('file_size')->nullable();
            $table->unsignedInteger('duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audio_audiobooks', function (Blueprint $table) {
            $table->dropColumn(['file_size', 'duration']);
        });
    }
}
