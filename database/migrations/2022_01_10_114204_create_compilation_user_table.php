<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompilationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compilation_user', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('compilation_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('compilation_id')->references('id')->on('compilations');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compilation_user');
    }
}
