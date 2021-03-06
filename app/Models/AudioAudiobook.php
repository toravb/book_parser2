<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioAudiobook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'book_id',
        'link',
        'doParse',
        'index',
        'public_path',
    ];

    public static function create($fields)
    {
        $audiobook = new static();
        $audiobook->fill($fields);
        $audiobook->save();

        return $audiobook;
    }

    public function book()
    {
        return $this->belongsTo(
            AudioBook::class,
            'book_id',
            'id',
        );
    }

}
