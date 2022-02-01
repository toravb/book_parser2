<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAudioBookIdColumnToRatesTable extends Migration
{
    public function up()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->foreignId('audio_book_id')->after('book_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('book_id')->nullable()->change();
            $table->unique(['audio_book_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropUnique(['audio_book_id', 'user_id']);
        });

        Schema::table('rates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('audio_book_id');
            $table->foreignId('book_id')->nullable(false)->change();
        });
    }
}

