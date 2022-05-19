<?php

namespace App\Models;

use App\Api\Interfaces\SearchModelInterface;
use App\Api\Traits\ElasticSearchTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model implements SearchModelInterface
{
    use HasFactory, ElasticSearchTrait;

    const ELASTIC_IDENTITY_KEY = '_book_series';

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

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'series_id', 'id');
    }

    public function getSeries($id): Series
    {
        return $this->with(['books' => function ($query) {
            return $query->select('id', 'year_id', 'series_id', 'title', 'text')
                ->with(['year', 'genres:id,name', 'authors:id,author', 'image:book_id,link'])
                ->withExists('userList as in_favorite')
                ->withCount(['rates', 'bookLikes', 'comments'])
                ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)');
        }])
            ->withCount('books')
            ->findOrFail($id);
    }

    public function baseSearchQuery(): Builder
    {
        return $this->with(['books' => function ($query) {
            return $query->with([
                'image' => function ($query) {
                    return $query->where('page_id', null)->select('book_id', 'link');
                },
            ])
                ->select('id', 'series_id')
                ->limit(1);
        }])
            ->withCount('books');
    }

    public function toSearchArray(): array
    {
        return [
            'title' => $this->series,
        ];
    }

    public function getElasticKey()
    {
        return $this->getKey() . self::ELASTIC_IDENTITY_KEY;
    }

    public function getSearchIndex(): string
    {
        return mb_strtolower(config('app.name') . 'series');
    }
}
