<?php

namespace App\Api\Filters;

use App\Models\Book;
use Illuminate\Http\Request;

class BookFilter extends QueryFilter
{
    public function __construct(Request $request)
    {
        //asd
        $this->request = $request;
    }

    public function showType(string $viewTypeList): \Illuminate\Database\Eloquent\Builder
    {
        if ($viewTypeList === Book::SHOW_TYPE_LIST) {
            return $this->builder->withCount(['bookLikes', 'bookComments'])
                ->with(['year', 'publishers',])
                ->addSelect('text');
        }

        return $this->builder;
    }

    public function findByAuthor(string $findByAuthor): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('authors', function ($query) use ($findByAuthor) {
            $query->where('author', 'like', '%' . $findByAuthor . '%');
        });
    }

    public function alphabetAuthorIndex(string $alphabetAuthorIndex): \Illuminate\Database\Eloquent\Builder
    {
        if ($alphabetAuthorIndex && !$this->request->findByAuthor) {
            return $this->builder->whereHas('authors', function ($query) use ($alphabetAuthorIndex) {
                $query->where('author', 'like', $alphabetAuthorIndex . '%');
            });
        }

        return $this->builder;
    }

    public function findByPublisher(string $findByPublisher): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('publishers', function ($query) use ($findByPublisher) {
            $query->where('publisher', 'like', '%' . $findByPublisher . '%');
        });
    }

    public function alphabetPublisherIndex(string $alphabetPublisherIndex): \Illuminate\Database\Eloquent\Builder
    {
        if ($alphabetPublisherIndex && !$this->request->findByPublisher) {
            return $this->builder->whereHas('publishers', function ($query) use ($alphabetPublisherIndex) {
                $query->where('publisher', 'like', $alphabetPublisherIndex . '%');
            });
        }

        return $this->builder;
    }

    public function findByTitle(string $findByTitle): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('title', 'like', '%' . $findByTitle . '%');
    }

    public function alphabetTitleIndex(string $alphabetTitleIndex): \Illuminate\Database\Eloquent\Builder
    {
        if ($alphabetTitleIndex && !$this->request->findByTitle) {
            return $this->builder->where('title', 'like', $alphabetTitleIndex . '%');
        }

        return $this->builder;
    }

    public function sortBy(string $sortBy): \Illuminate\Database\Eloquent\Builder
    {
        if ($sortBy === Book::SORT_BY_DATE) {
            return $this->builder->latest();
        }

        if ($sortBy === Book::SORT_BY_RATING) {
            return $this->builder->orderBy('rates_avg', 'desc');
        }

        if ($sortBy === Book::SORT_BY_READERS_COUNT) {
            return $this->builder->whereHas('bookStatuses', function ($query) {
                $query->reading();
            })
                ->withCount('bookStatuses as readersCount')
                ->orderBy('readersCount', 'desc');
        }

        if ($sortBy === Book::SORT_BY_ALPHABET) {
            return $this->builder->orderBy('title');
        }

        return $this->builder;
    }

    public function findByCategory(string $findByCategory): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('bookGenres', function ($query) use ($findByCategory) {
            $query->where('id', $findByCategory);
        });
    }
}
