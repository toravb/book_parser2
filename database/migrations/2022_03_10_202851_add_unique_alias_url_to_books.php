<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueAliasUrlToBooks extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unique('alias_url');
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique('books_alias_url_unique');
        });
    }
}
