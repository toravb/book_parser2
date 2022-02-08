<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearIdColumnToAudioBooksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('audio_books', 'year_id'))
        {
            Schema::table('audio_books', function (Blueprint $table) {

                $table->foreignId('year_id')->after('link_id')->constrained();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumns('audio_books', ['year_id', 'audio_books_year_id_foreign'])) {
            Schema::table('audio_books', function (Blueprint $table) {
                $table->dropConstrainedForeignId('year_id');
            });
        }
    }
}
