<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveColumnToAudioBooksTable extends Migration
{
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->unsignedTinyInteger('active')->after('id')->default(0);
        });
    }

    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
