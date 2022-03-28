<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerGenreTable extends Migration
{
    public function up()
    {
        Schema::create('banner_genre', function (Blueprint $table) {
            $table->foreignId('banner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner_genre');
    }
}
