<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLenghtTextColumn extends Migration
{
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('text', 1000)->change();
        });
    }

    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('text', 300)->change();
        });
    }
}
