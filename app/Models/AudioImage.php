<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'book_id',
        'doParse'
    ];

    public static function create($fields){
        $image = new static();
        $image->fill($fields);
        $image->save();

        return $image;
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
