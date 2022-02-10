<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookCommentReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('book_comment_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('book_review_id')->constrained();

            $table->text('content');

            $table->foreignId('parent_comment_id')
                ->nullable()
                ->constrained('book_comment_reviews', 'id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_comment_reviews');
    }
}
