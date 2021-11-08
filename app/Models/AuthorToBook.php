<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorToBook extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'author_id',
        'book_id'
    ];

    public static function create($fields){
        $authors = new static();
        $authors->fill($fields);
        $authors->save();

        return $authors;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }
}
