<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewTypes extends Migration
{
    public function up()
    {
        Schema::create('review_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_types');
    }
}
