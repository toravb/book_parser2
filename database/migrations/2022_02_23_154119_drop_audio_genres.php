<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAudioGenres extends Migration
{
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('genre_id');
        });

        Schema::dropIfExists('audio_genres');
    }
}
