<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioReadersToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_readers_to_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reader_id');
            $table->foreignId('book_id');
            $table->timestamps();
            $table->foreign('reader_id')
                ->references('id')
                ->on('audio_readers')
                ->onDelete('cascade');
            $table->foreign('book_id')
                ->references('id')
                ->on('audio_books')
                ->onDelete('cascade');
            $table->unique(['reader_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_readers_to_books');
    }
}
