<?php

namespace App\Api\Filters;

use App\Models\Book;
use App\Models\Compilation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompilationFilter extends QueryFilter
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function showType(string $showType)
    {
        if ($showType === 'list') {

            return $this->builder->with([
                'books' => function ($query) {
                    return $query->with(['authors', 'image'])
                        ->select('id', 'title')
                        ->withCount('rates')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                },
                'audioBooks' => function ($query) {
                    return $query
//                        ->with(['authors', 'image'])
                        ->select('id', 'title');
//                        ->withCount('rates')
//                        ->withAvg('rates as rates_avg', 'rates.rating');
                }

            ])
                ->select(['id', 'title']);

        }


        if ($showType === 'block') {
            return $this->builder
                ->select(['id', 'title', 'background'])
                ->withCount('books');

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

        return $this->builder;
    }
    public function compType(string $compType){
        if ($compType === Compilation::COMPILATION_USER) {
            return $this->builder;
        }
        if ($compType === Compilation::COMPILATION_ADMIN) {
            return $this->builder;
        }
        if ($compType === Compilation::COMPILATION_ALL) {
            return $this->builder;
        }
    }
}
