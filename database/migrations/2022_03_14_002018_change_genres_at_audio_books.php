<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGenresAtAudioBooks extends Migration
{
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('genre_id');
        });

        Schema::table('audio_books', function (Blueprint $table) {
            $table->foreignId('genre_id')->nullable()->constrained();
        });
    }

    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('genre_id');
        });

        Schema::table('audio_books', function (Blueprint $table) {
            $table->foreignId('genre_id')->nullable()->constrained('audio_genres');
        });
    }
}
