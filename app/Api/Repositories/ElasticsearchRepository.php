<?php

namespace App\Api\Repositories;

use App\Api\Interfaces\SearchModelInterface;
use App\Api\Interfaces\SearchRepositoryInterface;
use App\Api\Interfaces\Types;
use Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CommonCollection;
use App\Models\Series;
use App\Models\AudioSeries;

class ElasticsearchRepository implements SearchRepositoryInterface
{
    /** @var \Elasticsearch\Client */
    private $elasticsearch;
    protected array $searchableTypes;

    public function __construct(Client $elasticsearch, Types $types)
    {
        $this->elasticsearch = $elasticsearch;
        $this->searchableTypes = $types->getSearchableTypes();
    }

    public function search(string $query, int $limit, int $offset, string $modelType):  Collection|CommonCollection
    {
        $model = new $this->searchableTypes[$modelType];
        $items = $this->searchOnElasticsearch($query, $limit, $offset, $model);

        return $this->buildCollection($items, $model);
    }

    protected function searchOnElasticsearch(string $query, int $limit, int $offset, $model): array
    {
        $params = [
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'from' => $offset,
            'body' => [
                'size' => $limit,
                'query' => [
                    'multi_match' => [
                        'fields' => ['title'],
                        'query' => $query,
                        'fuzziness' => 'AUTO'
                    ],
                ],
            ],
        ];

        $items = $this->elasticsearch->search($params);

        return $items;
    }

    protected function buildCollection(array $items, SearchModelInterface $model): Collection|CommonCollection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return $model->baseSearchQuery()->findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            })->values();
    }
}
