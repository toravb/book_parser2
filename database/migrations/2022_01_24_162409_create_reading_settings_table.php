<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadingSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('reading_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('is_two_columns');
            $table->unsignedTinyInteger('font_size');
            $table->unsignedTinyInteger('screen_brightness');
            $table->string('font_name');
            $table->unsignedTinyInteger('field_size');
            $table->unsignedTinyInteger('row_height');
            $table->boolean('is_center_alignment');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reading_settings');
    }
}
