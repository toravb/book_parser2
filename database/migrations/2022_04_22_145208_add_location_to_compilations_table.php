<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToCompilationsTable extends Migration
{
    public function up()
    {
        Schema::table('compilations', function (Blueprint $table) {
            $table
                ->tinyInteger('location')
                ->after('type')
                ->unique()
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('compilations', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
}
