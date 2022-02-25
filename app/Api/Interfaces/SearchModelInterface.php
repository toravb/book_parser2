<?php

namespace App\Api\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface SearchModelInterface
{
    public function baseSearchQuery(): Builder;

    public static function bootSearchable();

    public function getSearchIndex();

    public function getSearchType();

    public function toSearchArray();

    public function getElasticKey();


}
