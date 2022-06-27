<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnQuotesTable extends Migration
{
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('start_text_index');
            $table->dropColumn('end_text_index');
        });
    }

    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->unsignedInteger('start_text_index')->after('start_key');
            $table->unsignedInteger('end_text_index')->after('end_key');
        });
    }
}
