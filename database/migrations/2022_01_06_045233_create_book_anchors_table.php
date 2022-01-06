<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookAnchorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_anchors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id');
            $table->unsignedSmallInteger('page_num');
            $table->string('anchor');
            $table->string('name', 1500);
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');

            $table->unique(['book_id', 'page_num', 'anchor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_anchors');
    }
}
