<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnTypeOnTypeIdCompilationsTable extends Migration
{
    public function up()
    {
        Schema::table('compilations', function (Blueprint $table) {
            $table->dropForeign(['type']);
            $table->renameColumn('type', 'type_id');
            $table->foreign('type_id')
            ->references('id')
            ->on('compilation_type')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('compilations', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->renameColumn('type_id', 'type');
            $table->foreign('type')
                ->references('id')
                ->on('compilation_type')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
}
