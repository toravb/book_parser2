<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLinkToNulableAtPages extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('link')->nullable(true)->change();
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('link')->nullable(false)->change();
        });
    }
}
