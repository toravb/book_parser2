<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookCompilationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_compilation', function (Blueprint $table) {
            $table->foreignId('compilation_id');
            $table->foreignId('compilationable_id');
            $table->string('compilationable_type');

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
        Schema::dropIfExists('book_compilation');
    }
}
