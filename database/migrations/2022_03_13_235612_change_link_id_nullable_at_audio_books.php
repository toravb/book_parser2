<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLinkIdNullableAtAudioBooks extends Migration
{
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->foreignId('link_id')->nullable(true)->change();
        });
    }

    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->foreignId('link_id')->nullable(false)->change();
        });
    }
}
