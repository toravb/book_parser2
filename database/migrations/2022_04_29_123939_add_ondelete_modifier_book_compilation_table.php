<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOndeleteModifierBookCompilationTable extends Migration
{
    public function up()
    {
        Schema::table('book_compilation', function (Blueprint $table) {
            $table->dropForeign('book_compilation_compilation_id_foreign');
            $table->dropIndex('book_compilation_compilation_id_foreign');
        });

        Schema::table('book_compilation', function (Blueprint $table) {
            $table->foreignId('compilation_id')->change()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });

    }

    public function down()
    {
        Schema::table('book_compilation', function (Blueprint $table) {
            $table->dropForeign('book_compilation_compilation_id_foreign');
        });

        Schema::table('book_compilation', function (Blueprint $table) {
            $table->foreign('compilation_id')->references('id')->on('compilations');
        });
    }
}
