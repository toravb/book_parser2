<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookUserTable extends Migration
{
    public function up()
    {
        Schema::create('audio_book_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('audio_book_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('status');
            $table->unique(['user_id', 'audio_book_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_book_user');
    }
}
