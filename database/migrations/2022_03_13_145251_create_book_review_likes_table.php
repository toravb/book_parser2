<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookReviewLikesTable extends Migration
{
    public function up()
    {
        Schema::create('book_review_likes', function (Blueprint $table) {
            $table->foreignId('book_review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_review_likes');
    }
}
