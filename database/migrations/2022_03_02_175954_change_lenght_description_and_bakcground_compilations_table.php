<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLenghtDescriptionAndBakcgroundCompilationsTable extends Migration
{
    public function up()
    {
        Schema::table('compilations', function (Blueprint $table) {
            $table->string('description', 10000)->change();
            $table->string('background', 255)->change();
        });
    }

    public function down()
    {
        Schema::table('compilations', function (Blueprint $table) {
            $table->string('description', 191)->change();
            $table->string('background', 191)->change();
        });
    }
}
