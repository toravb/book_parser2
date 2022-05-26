<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderByColumnToAudioBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->bigInteger('listeners_count')->unsigned()->index()->default(0)->nullable();
            $table->double('rate_avg')->unsigned()->index()->default(0)->nullable();
            $table->bigInteger('reviews_count')->unsigned()->index()->default(0)->nullable();
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('readers_count');
            $table->dropColumn('rate_avg');
            $table->dropColumn('reviews_count');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->bigInteger('readers_count')->unsigned()->index()->default(0)->nullable();
            $table->double('rate_avg')->unsigned()->index()->default(0)->nullable();
            $table->bigInteger('reviews_count')->unsigned()->index()->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropColumn('listeners_count');
            $table->dropColumn('rate_avg');
            $table->dropColumn('reviews_count');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('readers_count');
            $table->dropColumn('rate_avg');
            $table->dropColumn('reviews_count');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->bigInteger('readers_count')->unsigned()->index()->default(0);
            $table->double('rate_avg')->unsigned()->index()->default(0);
            $table->bigInteger('reviews_count')->unsigned()->index()->default(0);
        });
    }
}
