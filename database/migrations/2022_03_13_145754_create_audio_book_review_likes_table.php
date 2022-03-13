<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookReviewLikesTable extends Migration
{
    public function up()
    {
        Schema::create('audio_book_review_likes', function (Blueprint $table) {
            $table->foreignId('audio_book_review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_book_review_likes');
    }
}
