<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_images', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->foreignId('book_id');
            $table->boolean('doParse')->default(1)->index();
            $table->timestamps();
            $table->foreign('book_id')
                ->references('id')
                ->on('audio_books')
                ->onDelete('cascade');
            $table->unique(['link', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_images');
    }
}
