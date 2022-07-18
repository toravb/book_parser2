<?php

namespace App\Admin\Filters;

use Illuminate\Database\Eloquent\Builder;

class CompilationFilter extends QueryFilter
{
    public function search($search)
    {
        $this->builder->where(function (Builder $builder) use ($search) {
            $builder->orWhere('id', $search);
            $builder->orWhere('title', 'LIKE', "%{$search}%");
        });
    }
}
