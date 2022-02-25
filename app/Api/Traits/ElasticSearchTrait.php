<?php

namespace App\Api\Traits;

use App\Api\Observers\ElasticsearchObserver;

trait ElasticSearchTrait
{
    public static function bootSearchable()
    {
        static::observe(ElasticsearchObserver::class);
    }

    //название индекса, аналог базы в mysql
    public function getSearchIndex(): string
    {
        return mb_strtolower(config('app.name') . $this->getTable());
    }

    //название типа, аналог таблицы в mysql
    public function getSearchType(): string
    {
        return '_doc';
    }

    //поля, которые будем записывать в ElasticSearch
    public function toSearchArray(): array
    {
        return [
            'title' => $this->title
        ];
    }
}
