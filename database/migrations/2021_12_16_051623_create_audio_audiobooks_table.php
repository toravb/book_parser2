<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioAudiobooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_audiobooks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignId('book_id');
            $table->unsignedSmallInteger('index');
            $table->string('link');
            $table->boolean('doParse')->default(1)->index();
            $table->timestamps();
            $table->foreign('book_id')
                ->references('id')
                ->on('audio_books')
                ->onDelete('cascade');
            $table->unique(['book_id', 'link']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_audiobooks');
    }
}
