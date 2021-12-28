<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookBookGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_book_genres', function (Blueprint $table) {
            $table->foreignId('book_id');
            $table->foreign('book_id')
                ->references('id')
                ->on('books');
            $table->foreignId('book_genres_id');
            $table->foreign('book_genres_id')
                ->references('id')
                ->on('book_genres');
            $table->tinyInteger('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_book_genres');
    }
}
