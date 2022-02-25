<?php

namespace App\Api\Repositories;

use Illuminate\Database\Eloquent\Collection;

class AudioBookSearchRepository extends BookSearchRepository
{
    public function search(string $query, int $limit, int $offset, string $modelType): Collection
    {
        $model = new $this->searchableTypes[$modelType];
        $items = $this->searchOnElasticsearch($query, $limit, $offset, $model);
        $collection = $this->buildCollection($items, $model);
        foreach ($collection as $book) {
            if($book->rates_avg === null) {
                $book->rates_avg = 0;
            }
        }

        return $collection;
    }
}
