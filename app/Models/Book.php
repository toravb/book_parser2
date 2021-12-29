<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'series_id',
        'year_id',
        'link',
        'params',
        'text',
        'donor_id'
    ];

    public static function create($fields){
        $book = new static();
        $book->fill($fields);
        $book->save();

        return $book;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }

    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id', 'id');
    }

    public function pageLinks()
    {
        return $this->hasMany(PageLink::class, 'book_id', 'id');
    }

    public function authors()
    {
        return $this->belongsToMany(
            Author::class,
            AuthorToBook::class,
            'book_id',
            'author_id',
            'id',
            'id',
            'authors'
        );
    }

    public function publishers()
    {
        return $this->belongsToMany(
            Publisher::class,
            PublisherToBook::class,
            'book_id',
            'publisher_id',
            'id',
            'id',
            'publishers'
        );
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'book_id', 'id');
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'book_id', 'id');
    }

    public function images()
    {
        return $this->hasManyThrough(
            Page::class,
            Image::class,
            'book_id',
            'page_id',
            'id',
            'id'
        );
    }

    public function genre()
    {
        return $this->hasOne(BookGenre::class, 'id', 'genre_id');
    }

    public function bookGenres(){
        return $this->belongsToMany(BookGenre::class);
    }
}
