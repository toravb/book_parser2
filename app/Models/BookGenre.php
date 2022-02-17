<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGenre extends Model
{
    use HasFactory;
    protected $hidden = ['pivot'];



    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function booksCount()
    {
        return $this->withCount('books')->get();
    }

    public function index()
    {
        return $this->orderBy('name', 'asc')->get();
    }

}
