<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'series'
    ];

    public static function create($fields)
    {
        $series = new static();
        $series->fill($fields);
        $series->save();

        return $series;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'series_id', 'id');
    }

    public function booksFullData()
    {
//        $book = new Book();
//        $a = $book->getBook()->;
//        dd($a);
        return $this->books()->with([
            'authors',
            'image',
            'bookGenres',
//            'bookStatuses'
        ])
            ->select('id', 'title', 'series_id')
            ->withCount('rates')
            ->withAvg('rates as rates_avg', 'rates.rating');
    }
}
