<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCompilation extends Model
{
    use HasFactory;
    protected $table = 'book_compilation';
    public $timestamps = false;
    protected $hidden = ['pivot'];


    public function saveBookToCompilation (int $compilationId, int $bookId, string $bookType)
    {

        $this->compilation_id = $compilationId;
        $this->compilationable_id = $bookId;
        $this->compilationable_type = $bookType;
        $this->save();
    }
    public function deleteBookFromCompilation (int $compilationId, int $bookId, string $bookType)
    {

        $this->where('compilation_id', $compilationId)
            ->where('compilationable_id', $bookId)
            ->where('compilationable_type', $bookType)
            ->delete();
    }

    public function bookCompilationable(){
        return $this->morphTo('bookCompilationable', 'compilationable_type', 'compilationable_id');
    }



}
