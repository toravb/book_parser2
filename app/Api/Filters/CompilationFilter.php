<?php

namespace App\Api\Filters;

use App\Models\Compilation;
use Illuminate\Http\Request;

class CompilationFilter extends QueryFilter
{
    public function showType(string $showType)
    {
        if ($showType === 'list') {

            return $this->builder->with([
                'books' => function ($query) {
                    return $query->with(['authors:author', 'image:book_id,link'])
                        ->select('id', 'title')
                        ->withCount('rates')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                },
                'audioBooks' => function ($query) {
                    return $query
                        ->with(['authors:author', 'image:book_id,link'])
                        ->select('id', 'title')
                        ->withCount('rates')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                }

            ])
                ->select(['id', 'title']);

        }

        if ($showType === 'block') {
            return $this->builder
                ->select(['id', 'title', 'background'])
                ->withCount(['books', 'audioBooks']);

        }
    }


    public function selectionCategory(string $selectionCategory)
    {

        return $this->builder->where('type', $selectionCategory);
    }

    public function bookType(string $bookType)
    {

        return $this->builder->whereHas($bookType);

    }

    public function sortBy(string $sortBy): \Illuminate\Database\Eloquent\Builder
    {
        if ($sortBy === Compilation::SORT_BY_DATE) {
            return $this->builder->latest();
        }

        if ($sortBy === Compilation::SORT_BY_ALPHABET) {
            return $this->builder->orderBy('title');
        }

        if ($sortBy === Compilation::SORT_BY_VIEWS){
            return $this->builder->withCount('views')->orderBy('views_count', 'desc');
        }

        return $this->builder;
    }

    public function compType(string $compType)
    {
        return $this->builder
            ->select('id', 'title', 'background')
            ->when($compType === Compilation::COMPILATION_USER, function ($query) {
                $query->where('created_by', \auth()->id());
            })->when($compType === Compilation::COMPILATION_ADMIN, function ($query) {
                $query->orWhereNotNull('type');
            })->withCount(['books', 'audioBooks']);
    }

    public function letter(string $letter)
    {
        return $this->builder->where('title', 'like', '%' . $letter . '%');
    }

}
