<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'link',
        'doParse',
        'book_id',
        'page_id'
    ];

    public static function create($fields){
        $image = new static();
        $image->fill($fields);
        $image->save();

        return $image;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }
}
