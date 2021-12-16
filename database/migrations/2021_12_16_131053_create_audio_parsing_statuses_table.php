<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioParsingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_parsing_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id');
            $table->boolean('status_id')->index();
            $table->boolean('doParse')->default(false);
            $table->string('status')->nullable();
            $table->timestamps();
            $table->foreign('site_id')
                ->references('id')
                ->on('audio_sites')
                ->onDelete('cascade');
            $table->unique(['site_id', 'status_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_parsing_statuses');
    }
}
