<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParsingStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parsing_status', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->string('parse_type', 10);
            $table->integer('Progress')->nullable();
            $table->integer('Count')->nullable();
            $table->boolean('Status')->nullable();
            $table->timestamp('last_parsing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parsing_status');
    }
}
