<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsHiddenToGenres extends Migration
{
    public function up()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false);
        });
    }

    public function down()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropColumn(['is_hidden']);
        });
    }
}
