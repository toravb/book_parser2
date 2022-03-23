<?php

namespace App\Admin\Filters;

use Illuminate\Database\Eloquent\Builder;

class AudioBookFilter extends QueryFilter
{
    public function search($search)
    {
        $this->builder->where(function (Builder $builder) use ($search) {
            $builder->orWhere('id', $search);
            $builder->orWhere('title', 'LIKE', "%{$search}%");
        });
    }

    public function sortByYear($direction)
    {
        $this->builder->join('years', 'audio_books.year_id', '=', 'years.id')
            ->orderBy('years.year', $direction);
    }
}
