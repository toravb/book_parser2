<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCompilation extends Model
{
    use HasFactory;

    protected $table = 'book_compilation';

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function compilations()
    {
        return $this->belongsToMany(Compilation::class);
    }

}
