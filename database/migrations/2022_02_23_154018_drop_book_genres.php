<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBookGenres extends Migration
{
    public function up()
    {
        Schema::dropIfExists('book_genres');
    }
}
