<?php

namespace App\Api\Repositories;

use Illuminate\Database\Eloquent\Collection;

class CompilationSearchRepository extends ElasticsearchRepository
{
    public function search(string $query, int $limit, int $offset, string $modelType): Collection
    {
        $model = new $this->searchableTypes[$modelType];
        $items = $this->searchOnElasticsearch($query, $limit, $offset, $model);
        $collection = $this->buildCollection($items, $model);
        foreach ($collection as $authors) {
            $authors->books_count = $authors->books_count + $authors->audio_books_count;
            unset($authors->audio_books_count);
        }

        return $collection;
    }
}
