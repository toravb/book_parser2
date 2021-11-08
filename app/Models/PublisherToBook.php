<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublisherToBook extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'publisher_id',
        'book_id'
    ];

    public static function create($fields){
        $publishers = new static();
        $publishers->fill($fields);
        $publishers->save();

        return $publishers;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }
}
