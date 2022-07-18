<?php

namespace App\Api\Filters;

use App\Models\Compilation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompilationFilter extends QueryFilter
{

    public function showType(string $showType)
    {
        if ($showType === 'list') {

            return $this->builder->with([
                'books' => function ($query) {
                    return $query->with(['authors:id,author', 'image:book_id,link', 'genres:id,name'])
                        ->select('id', 'title')
                        ->withCount('rates')
                        ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)');
                },
                'audioBooks' => function ($query) {
                    return $query
                        ->with(['authors:id,author', 'image:book_id,link', 'genre:id,name'])
                        ->select('id', 'title', 'genre_id')
                        ->withCount('rates')
                        ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)');
                }
            ])
                ->select(['id', 'title'])
                ->withExists('compilationUsers as in_favorite');
        }

        if ($showType === 'block') {
            return $this->builder
                ->select(['id', 'title', 'background'])
                ->withCount(['books', 'audioBooks']);

        }
    }


    public function selectionCategory(string $selectionCategory): Builder
    {
        if ($selectionCategory === Compilation::CATEGORY_ALL) {
            return $this->builder->where('location', null);
        }

        return $this->builder->where('type_id', $selectionCategory);
    }

    public function bookType(string $bookType): Builder
    {
        if ($bookType === QueryFilter::TYPE_ALL) {
            return $this->builder;
        }

        return $this->builder->whereHas($bookType);

    }

    public function sortBy(string $sortBy): Builder
    {
        if ($sortBy === Compilation::SORT_BY_DATE) {
            return $this->builder->latest();
        }

        if ($sortBy === Compilation::SORT_BY_ALPHABET) {
            return $this->builder->orderBy('title');
        }

        if ($sortBy === Compilation::SORT_BY_VIEWS) {
            return $this->builder->withCount('views')->orderBy('views_count', 'desc');
        }

        return $this->builder;
    }

    public function compType(string $compType)
    {
        return $this->builder
            ->select('id', 'title', 'background', 'created_by')
            ->when($compType === Compilation::COMPILATION_USER, function ($query) {
                $query->where('created_by', \auth()->id());
            })->when($compType === Compilation::COMPILATION_ADMIN, function ($query) {
                $query->whereNotNull('type_id')->whereHas('compilationUsers');
            })->when($compType === Compilation::COMPILATION_ALL, function ($query) {
                $query->where('created_by', \auth()->id())->orWhereHas('compilationUsers');
            })
            ->withCount(['books', 'audioBooks']);
    }

    public function letter(string $letter)
    {
        return $this->builder->where('title', 'like', '%' . $letter . '%');
    }

}
