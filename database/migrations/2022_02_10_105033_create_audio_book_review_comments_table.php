<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookReviewCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('audio_book_review_comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('audio_book_review_id')->constrained();

            $table->text('content');

            $table->foreignId('parent_comment_id')
                ->nullable()
                ->constrained('audio_book_review_comments', 'id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_book_review_comments');
    }
}
