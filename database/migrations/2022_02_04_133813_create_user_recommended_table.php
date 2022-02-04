<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRecommendedTable extends Migration
{
    public function up()
    {
        Schema::create('users_recommended', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('book_id') ->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('audio_book_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('content', 250);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_recommended');
    }
}
