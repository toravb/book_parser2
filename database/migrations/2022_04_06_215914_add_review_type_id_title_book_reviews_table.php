<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewTypeIdTitleBookReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('book_reviews', function (Blueprint $table) {
            $table->foreignId('review_type_id')->after('book_id')->default(1)->constrained();
            $table->string('title', 150)->after('review_type_id');
        });
    }

    public function down()
    {
        Schema::table('book_reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('review_type_id');
            $table->dropColumn('title');
        });
    }
}
