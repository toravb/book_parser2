<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('audio_book_comments', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->foreignId("user_id")->constrained();
            $table->foreignId('audio_book_id')->constrained();
            $table->text('content');
            $table->unsignedBigInteger('parent_comment_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_book_comments');
    }
}
