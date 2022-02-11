<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAudioAuthorsToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('audio_authors_to_books');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('audio_authors_to_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id');
            $table->foreignId('book_id');
            $table->timestamps();
            $table->foreign('author_id')
                ->references('id')
                ->on('audio_authors')
                ->onDelete('cascade');
            $table->foreign('book_id')
                ->references('id')
                ->on('audio_books')
                ->onDelete('cascade');
            $table->unique(['author_id', 'book_id']);

        });
    }

}
