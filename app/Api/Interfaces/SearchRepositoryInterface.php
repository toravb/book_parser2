<?php

namespace App\Api\Interfaces;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CommonCollection;

interface SearchRepositoryInterface
{
    public function search(string $query, int $limit, int $offset, string $modelType): Collection|CommonCollection;
}
