<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoDataToBooks extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('alias_url')->nullable();
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'meta_description',
                'meta_keywords',
                'alias_url',
            ]);
        });
    }
}
