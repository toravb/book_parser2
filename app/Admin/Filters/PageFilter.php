<?php

namespace App\Admin\Filters;

use Illuminate\Database\Eloquent\Builder;

class PageFilter extends QueryFilter
{
    public function search($search)
    {
        $this->builder->where(function (Builder $q) use ($search) {
           $q->orWhere('id', $search);
           $q->orWhere('page_number', $search);
           $q->orWhere('content','LIKE', "%{$search}%");
        });
    }
}
