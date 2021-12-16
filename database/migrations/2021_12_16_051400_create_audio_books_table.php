<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 1000);
            $table->text('description')->nullable();
            $table->json('params')->nullable();
            $table->foreignId('genre_id')->nullable();
            $table->foreignId('series_id')->nullable();
            $table->foreignId('link_id')->unique();
            $table->boolean('litres')->default(false);
            $table->timestamps();
            $table->foreign('genre_id')
                ->references('id')
                ->on('audio_genres')
                ->onDelete('cascade');
            $table->foreign('series_id')
                ->references('id')
                ->on('audio_series')
                ->onDelete('cascade');
            $table->foreign('link_id')
                ->references('id')
                ->on('audio_books_links')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_books');
    }
}
