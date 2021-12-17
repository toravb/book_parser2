<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioReader extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public static function create($fields){
        $reader = new static();
        $reader->fill($fields);
        $reader->save();

        return $reader;
    }

    public function books()
    {
        return $this->hasManyThrough(
            AudioBook::class,
            AudioReadersToBook::class,
            'reader_id',
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
