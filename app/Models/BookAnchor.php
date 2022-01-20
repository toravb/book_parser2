<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookAnchor extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function bookContents(int $bookId){
        $bookContents = BookAnchor::where('book_id', $bookId)->get();
        return $bookContents;
    }
}
