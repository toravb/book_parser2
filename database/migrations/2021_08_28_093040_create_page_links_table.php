<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id');
            $table->string('link',120);
            $table->boolean('doParse')->default(true);
            $table->foreign('book_id')
                    ->references('id')
                    ->on('books')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_links');
    }
}
