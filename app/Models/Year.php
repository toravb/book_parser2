<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'year'
    ];

    public static function create($fields){
        $year = new static();
        $year->fill($fields);
        $year->save();

        return $year;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'year_id', 'id');
    }

    public function audioBooks()
    {
        return $this->hasMany(AudioBook::class);
    }
}
