<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveGenreIdColumnFromBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('books', 'genre_id'))
        {
            Schema::table('books', function (Blueprint $table)
            {
                $table->dropForeign(['genre_id']);
                $table->dropColumn(['genre_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('genre_id')->nullable();
            $table->foreign('genre_id')
                ->references('id')
                ->on('book_genres')
                ->onDelete('cascade');
        });
    }
}
