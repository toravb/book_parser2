<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioAuthor extends Model
{
    use HasFactory;

    protected $table = 'authors';

    public static function create($fields){
        $author = new static();
        $author->fill($fields);
        $author->save();

        return $author;
    }

    public function books()
    {
        return $this->hasManyThrough(
            AudioBook::class,
            AuthorsToAudioBook::class,
            'author_id',
            'id',
            'id',
            'book_id'
        )->with('image')
            ->with('genre')
            ->with('series')
            ->with('authors')
            ->with('actors');
    }
}
