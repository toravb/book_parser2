<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForeignKeyAudioBookReviewCommentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_book_review_comment_likes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('audio_review_comment_id');
        });

        Schema::table('audio_book_review_comment_likes', function (Blueprint $table) {
            $table->foreignId('audio_review_comment_id')
                ->references('id')
                ->on('audio_book_review_comments')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audio_book_review_comment_likes', function (Blueprint $table) {
            $table->dropForeign('audio_review_comment_id');
        });
    }
}
