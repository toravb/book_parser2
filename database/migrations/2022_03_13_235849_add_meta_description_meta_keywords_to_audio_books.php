<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaDescriptionMetaKeywordsToAudioBooks extends Migration
{
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
        });
    }

    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropColumn([
               'meta_description',
               'meta_keywords',
            ]);
        });
    }
}
