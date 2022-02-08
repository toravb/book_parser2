<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameReviewsToBookReviews extends Migration
{
    public function up()
    {
        Schema::rename('reviews', 'book_reviews');
    }

    public function down()
    {
        Schema::rename('book_reviews', 'reviews');
    }
}
