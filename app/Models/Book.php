<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

//    public $timestamps = false;

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
//        protected $hidden = ['pivot'];

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

    public function bookGenres()
    {
        return $this->belongsToMany(BookGenre::class);
    }

    public function rates()
    {
        return $this->belongsToMany(User::class, 'rates');
    }

    public function bookComments()
    {
        return $this->hasMany(BookComment::class);
    }
    public function bookLikes()
    {
        return $this->hasMany(BookLike::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
    public function bookStatuses()
    {
        return $this->hasMany(BookUser::class);
    }

    public function scopeNewest ($query)
    {
        return $query->latest();
    }

    public function genres()
    {
        return $this->hasManyThrough(
            BookGenre::class,
            BookBookGenre::class,
            'book_id',
            'id',
            'id',
            'book_genres_id'
        );
    }

    public function anchors()
    {
        return $this->hasMany(BookAnchor::class, 'book_id', 'id');
    }
}
