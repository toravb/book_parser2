<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsToAudioBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors_to_audio_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id');
            $table->foreignId('book_id');
            $table->timestamps();
            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onDelete('cascade');
            $table->foreign('book_id')
                ->references('id')
                ->on('audio_books')
                ->onDelete('cascade');
            $table->unique(['author_id', 'book_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors_to_audio_books');
    }
}
