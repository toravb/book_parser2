<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompilationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compilations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('background');
            $table->string('description');
            $table->foreignId('created_by');
            $table->foreignId('type')->nullable();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('type')->references('id')->on('compilation_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compilations');
    }
}
