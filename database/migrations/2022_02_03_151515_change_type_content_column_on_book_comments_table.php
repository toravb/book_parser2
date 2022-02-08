<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeContentColumnOnBookCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('book_comments', function (Blueprint $table) {
            $table->text('content')->change();
        });
    }

    public function down()
    {
        Schema::table('book_comments', function (Blueprint $table) {
            $table->string('content')->change();
        });
    }
}
