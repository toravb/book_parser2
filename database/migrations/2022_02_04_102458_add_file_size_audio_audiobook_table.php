<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileSizeAudioAudiobookTable extends Migration
{
    public function up()
    {
        Schema::table('audio_audiobooks', function (Blueprint $table) {
            $table->bigInteger('file_size')->after('link');
        });
    }

    public function down()
    {
        Schema::table('audio_audiobooks', function (Blueprint $table) {
            $table->dropColumn('file_size');
        });
    }
}
