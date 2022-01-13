<?php

namespace App\Api\Services;


use App\Models\Compilation;

class CompilationService extends Compilation
{
    public function storeCompilation(string $title, string $backgroud, string $description, int $created_by, int $type = null)
    {
        $table = new Compilation();
        $table->title = $title;
        $table->background = $backgroud;
        $table->description = $description;
        $table->created_by = $created_by;
        $table->type = $type;
        $table->save();


    }



}
