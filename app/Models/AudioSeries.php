<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public static function create($fields){
        $series = new static();
        $series->fill($fields);
        $series->save();

        return $series;
    }

    public function books()
    {
        return $this->hasMany(
            AudioBook::class,
            'series_id',
            'id'
        )->with('image')
            ->with('genre')
            ->with('series')
            ->with('authors')
            ->with('actors');
    }
}