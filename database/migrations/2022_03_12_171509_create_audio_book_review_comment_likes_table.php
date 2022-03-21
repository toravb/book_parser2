<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookReviewCommentLikesTable extends Migration
{
    public function up()
    {
        Schema::create('audio_book_review_comment_likes', function (Blueprint $table) {
            $table->foreignId('audio_review_comment_id')
                ->references('id')
                ->on('book_review_comments')
                ->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_book_review_comment_likes');
    }
}
