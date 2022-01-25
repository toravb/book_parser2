<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Author extends Model
{
    use HasFactory, HasRelationships;

    public $timestamps = false;

    protected $fillable = [
        'author',
        'about',
        'avatar'
    ];
    protected $hidden = ['pivot'];
    public static function create($fields)
    {
        $author = new static();
        $author->fill($fields);
        $author->save();

        return $author;
    }

    public function edit($fields)
    {
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

    public function audioBooks(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
    {
        return $this->hasManyDeep(AudioBook::class, [AudioAuthor::class, AudioAuthorsToBook::class],
            [
                'id',
                'author_id',
                'id',
            ],
            [
                'audio_author_id',
                'id',
                'book_id',
            ],
        );
    }

    public function series(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
    {
        return $this->hasManyDeep(Series::class, [AuthorToBook::class],
            [
                'author_id',
                'book_id',
                'id',
                'id',
            ]);
    }


    public function authorReviews(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
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

    public function audioAuthor()
    {
        return $this->belongsTo(AudioAuthor::class);
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
