<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimFormsTable extends Migration
{
    public function up()
    {
        Schema::create('claim_forms', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('link_source');
            $table->string('link_content');
            $table->string('name');
            $table->string('email');
            $table->boolean('agreement');
            $table->boolean('copyright_holder');
            $table->boolean('interaction');


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_forms');
    }
}
