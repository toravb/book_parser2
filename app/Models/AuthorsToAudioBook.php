<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorsToAudioBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'book_id',
    ];

    public static function create($fields){
        $rel = new static();
        $rel->fill($fields);
        $rel->save();

        return $rel;
    }
}
