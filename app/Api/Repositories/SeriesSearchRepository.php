<?php

namespace App\Api\Repositories;

use App\Api\Interfaces\SearchModelInterface;
use App\Models\AudioSeries;
use App\Models\Series;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class SeriesSearchRepository extends ElasticsearchRepository
{
    protected function buildCollection(array $items, SearchModelInterface $model): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        $audioSeriesId = [];
        $bookSeriesId = [];
        foreach ($ids as $id) {
            $pieces = explode("_", $id);
            if ($pieces[1] === 'book') {
                $bookSeriesId[] = $pieces[0];
            } else {
                $audioSeriesId[] = $pieces[0];
            }
        }
        $bookSeriesModel = new Series();
        $books = $bookSeriesModel->baseSearchQuery()->findMany($bookSeriesId);
        $audioBookModel = new AudioSeries();
        $audioBooks = $audioBookModel->baseSearchQuery()->findMany($audioSeriesId);
        $merged = $audioBooks->concat($books);

        return $merged->sortBy(function ($series) use ($ids) {
            return array_search($series->getElasticKey(), $ids);
        });
    }
}
