<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'author'
    ];

    protected $hidden = ['pivot'];

    public static function create($fields){
        $author = new static();
        $author->fill($fields);
        $author->save();

        return $author;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'author_id', 'id');
    }

}
