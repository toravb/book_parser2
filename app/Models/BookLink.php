<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLink extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'link',
        'donor_id',
        'doParse'
    ];

    public static function create($fields){
        $book_link = new static();
        $book_link->fill($fields);
        $book_link->save();

        return $book_link;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }
}
