<?php

namespace App\Api\Observers;

use Elasticsearch\Client;

class ElasticsearchObserver
{
    public function __construct(private Client $elasticsearch)
    {
    }

    //обновляем индекс при изменении данных в модели
    public function saved($model)
    {
        $this->elasticsearch->index([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->getkey(),
            'body' => $model->toSearchArray()
        ]);
    }

    //удаляем из эластика при удалении из таблицы
    public function delete($model)
    {
        $this->elasticsearch->delete([
            'index' => $model->getSearchindex(),
            'type ' => $model->getSearchType(),
            'id' => $model->getkey()
        ]);
    }
}
