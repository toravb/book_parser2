<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'params',
        'genre_id',
        'series_id',
        'link_id',
        'litres'
    ];

    public static function create($fields){
        $book = new static();
        $book->fill($fields);
        $book->save();

        return $book;
    }

    public function images()
    {
        return $this->hasMany(
            AudioImage::class,
            'book_id',
            'id',
        );
    }

    public function image()
    {
        return $this->hasOne(
            AudioImage::class,
            'book_id',
            'id',
        );
    }

    public function audiobook()
    {
        return $this->hasOne(
            AudioAudiobook::class,
            'book_id',
            'id',
        );
    }

    public function audiobooks()
    {
        return $this->hasMany(
            AudioAudiobook::class,
            'book_id',
            'id',
        );
    }

    public function genre()
    {
        return $this->belongsTo(
            AudioGenre::class,
            'genre_id',
            'id'
        );
    }

    public function series()
    {
        return $this->belongsTo(
            AudioSeries::class,
            'series_id',
            'id'
        );
    }

    public function authors()
    {
        return $this->hasManyThrough(
            AudioAuthor::class,
            AudioAuthorsToBook::class,
            'book_id',
            'id',
            'id',
            'author_id'
        );
    }

    public function actors()
    {
        return $this->hasManyThrough(
            AudioReader::class,
            AudioReadersToBook::class,
            'book_id',
            'id',
            'id',
            'reader_id'
        );
    }
}
