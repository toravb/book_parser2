<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'publisher'
    ];

    public static function create($fields){
        $publisher = new static();
        $publisher->fill($fields);
        $publisher->save();

        return $publisher;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'publisher_id', 'id');
    }
}
