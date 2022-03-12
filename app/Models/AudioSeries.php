<?php

namespace App\Models;

use App\Api\Traits\ElasticSearchTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Api\Interfaces\SearchModelInterface;

class AudioSeries extends Model implements SearchModelInterface
{
    use HasFactory, ElasticSearchTrait;

    const ELASTIC_IDENTITY_KEY = '_audio_series';

    protected $fillable = [
        'name',
    ];

    public static function create($fields)
    {
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

    public function baseSearchQuery(): Builder
    {
        return $this->with(['books' => function ($query) {
            return $query->with([
                'image' => function ($query) {
                    return $query->select('book_id', 'link');
                },
            ])->limit(1);
        }])
            ->withCount('books');
    }

    public function toSearchArray(): array
    {
        return [
            'title' => $this->name,
        ];
    }

    public function getElasticKey() {
        return $this->getKey() . self::ELASTIC_IDENTITY_KEY;
    }

    public function getSearchIndex(): string
    {
        return mb_strtolower(config('app.name') . 'series');
    }
}
