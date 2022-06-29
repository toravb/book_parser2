<?php

namespace App\Api\Repositories;

use App\Api\Interfaces\SearchModelInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as CommonCollection;

class AuthorSearchRepository extends ElasticsearchRepository
{
    public function search(string $query, int $limit, int $offset, string $modelType): Collection|CommonCollection
    {
        $model = new $this->searchableTypes[$modelType];
        $items = $this->searchOnElasticsearch($query, $limit, $offset, $model);

        $collection = $this->buildCollection($items, $model);
        $newCollection = [];
        foreach ($collection as $authors) {
            $authors['books_count'] = $authors['books_count'] + $authors['audio_books_count'];
            unset($authors['audio_books_count']);
            $newCollection[] = $authors;
        }

        return collect($newCollection);
    }

    protected function buildCollection(array $items, SearchModelInterface $model): Collection|CommonCollection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        $sortedModels = $model->baseSearchQuery()->findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            })->values();

        return collect(array_values($sortedModels->toArray()));
    }
}
