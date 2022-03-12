<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookGenre extends Migration
{
    public function up()
    {
        Schema::create('audio_book_genre', function (Blueprint $table) {
            $table->foreignId('audio_book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_book_genre');
    }
}
