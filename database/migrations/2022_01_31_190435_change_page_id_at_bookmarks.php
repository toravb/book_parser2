<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePageIdAtBookmarks extends Migration
{
    public function up()
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('page_id');
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->integer('page_id')->unsigned();
        });
    }
}
