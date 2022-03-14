<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugUniqueToAudioBooks extends Migration
{
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->string('alias_url')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropUnique('audio_books_alias_url_unique');
            $table->dropColumn('alias_url');
            $table->string('slug', 1000);
        });
    }
}
