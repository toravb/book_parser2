<?php

namespace App\Admin\Filters;

use App\Api\Filters\QueryFilter as ApiQueryFilter;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter extends ApiQueryFilter
{
    protected string $sortByNeedle = 'sortBy';

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->fields() as $field => $value) {
            $method = $field;
            if (is_callable([$this, $method]) and method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            } elseif (str_contains($method, $this->sortByNeedle)) {
                $sortField = strtolower(substr($method, strlen($this->sortByNeedle)));
                $this->sortBy($sortField, $value);
            }
        }
    }

    protected function sortBy(string $name, string $direction)
    {
        $this->builder->orderBy($name, $direction);
    }
}
