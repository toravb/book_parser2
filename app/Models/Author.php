<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'author',
        'about',
        'avatar'
    ];

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
        return $this->belongsToMany(Book::class,

            AuthorToBook::class,
            'author_id',
            'book_id',
            'id',
            'id',
            'books');
    }

    public function authorReviews()
    {
        return $this->hasManyThrough(
            Review::class,
            AuthorToBook::class,
            'author_id',
            'book_id',
            'authors.id',
            'book_id'
        );
    }
    public function authorQuotes()
    {
        return $this->hasManyThrough(
            Quote::class,
            AuthorToBook::class,
            'author_id',
            'book_id',
            'authors.id',
            'book_id'
        );
    }
    public function similarAuthors()
    {
        return $this->hasMany(
            SimilarAuthors::class,
        'author_id_from',
        'id');
    }
/*    public function getAuthor(){
        return
            $this->with([
                'author',
                'avatar',
                'about',

            ]);
    }*/
}
