<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioAuthorsLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'doParse',
    ];

    public static function create($fields){
        $link = new static();
        $link->fill($fields);
        $link->save();

        return $link;
    }
}
