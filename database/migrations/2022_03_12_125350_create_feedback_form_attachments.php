<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackFormAttachments extends Migration
{
    public function up()
    {
        Schema::create('feedback_form_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_form_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('storage_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback_form_attachments');
    }
}
