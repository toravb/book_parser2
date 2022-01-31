<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearIdColumnToAudioBooksTable extends Migration
{
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->foreignId('year_id')->after('link_id')->constrained();
        });
    }

    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('year_id');
        });
    }
}
