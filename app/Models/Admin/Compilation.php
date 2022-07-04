<?php

namespace App\Models\Admin;

use App\Models\BookCompilation;
use Illuminate\Database\Eloquent\Model;

class Compilation extends \App\Models\Compilation
{
public function addBookToNovelties(int $bookID)
{
    $compilations = new Compilation();
    $noveltiesCompilation = new BookCompilation();

    $compilation = $compilations->where('location', 1)->first();

    $noveltiesCompilation->compilation_id = $compilation->id;
    $noveltiesCompilation->compilationable_id = $bookID;
    $noveltiesCompilation->compilationable_type = 'books';
    $noveltiesCompilation->save();

    return$noveltiesCompilation;

}
}
