<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioGenre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public static function create($fields){
        $genre = new static();
        $genre->fill($fields);
        $genre->save();

        return $genre;
    }
}
