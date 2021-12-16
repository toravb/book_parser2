<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioReadersToBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'reader_id',
        'book_id',
    ];

    public static function create($fields){
        $rel = new static();
        $rel->fill($fields);
        $rel->save();

        return $rel;
    }
}
