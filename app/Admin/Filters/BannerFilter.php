<?php

namespace App\Admin\Filters;

use Illuminate\Database\Eloquent\Builder;

class BannerFilter extends QueryFilter
{
    public function search($search)
    {
        $this->builder->where(function (Builder $builder) use ($search) {
            $builder->orWhere('id', $search);
            $builder->orWhere('name', 'LIKE', "%{$search}%");
        });
    }

}
