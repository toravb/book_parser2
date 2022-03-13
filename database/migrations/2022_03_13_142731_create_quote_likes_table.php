<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteLikesTable extends Migration
{
    public function up()
    {
        Schema::create('quote_likes', function (Blueprint $table) {
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quote_likes');
    }
}
