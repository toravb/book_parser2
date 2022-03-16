<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsQuotesTable extends Migration
{
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('position');
            $table->renameColumn('content', 'text');
            $table->string('start_key')->after('color');
            $table->unsignedInteger('start_text_index')->after('start_key');
            $table->unsignedInteger('start_offset')->after('start_text_index');
            $table->string('end_key')->after('start_offset');
            $table->unsignedInteger('end_text_index')->after('end_key');
            $table->unsignedInteger('end_offset')->after('end_text_index');
        });
    }

    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->unsignedInteger('position')->after('page_id');
            $table->renameColumn('text', 'content');
            $table->dropColumn('start_key');
            $table->dropColumn('start_text_index');
            $table->dropColumn('start_offset');
            $table->dropColumn('end_key');
            $table->dropColumn('end_text_index');
            $table->dropColumn('end_offset');
        });
    }
}
