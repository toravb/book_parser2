<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('audio_book_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('user_id')->constrained();
            $table->foreignId('audio_book_id')->constrained();
            $table->foreignId('review_type_id')->constrained();
            $table->string('title', 150);
            $table->text('content');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_book_reviews');
    }
}
