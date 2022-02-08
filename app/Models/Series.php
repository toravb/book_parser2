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

    public function create($fields)
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

    public function getSeries($id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->with(['books' => function ($query) {
            return $query->select('id', 'year_id', 'series_id', 'title', 'link', 'text')
                ->with(['year', 'genres', 'authors'])
                ->withCount(['rates', 'bookLikes', 'сomments'])
                ->withAvg('rates as rates_avg', 'rates.rating');
        }])->withCount('books');
    }
}
