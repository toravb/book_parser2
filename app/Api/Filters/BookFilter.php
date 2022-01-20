<?php

namespace App\Api\Filters;

use App\Models\Book;
use Illuminate\Http\Request;

class BookFilter extends QueryFilter
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function showType(string $viewTypeList)
    {

        if ($viewTypeList === Book::SHOW_TYPE_LIST) {
            return $this->builder->withCount(['bookLikes', 'bookComments'])
                ->with(['year', 'publishers',])
                ->addSelect('text');
        }
    }

    public function findByAuthor(string $findByAuthor)
    {
        return $this->builder->whereHas('authors', function ($query) use ($findByAuthor) {
            $query->where('author', 'like', '%' . $findByAuthor . '%');
        });
    }

    public function alphabetAuthorIndex(string $alphabetAuthorIndex)
    {
        if ($alphabetAuthorIndex && !$this->request->findByAuthor) {
            return $this->builder->whereHas('authors', function ($query) use ($alphabetAuthorIndex) {
                $query->where('author', 'like', $alphabetAuthorIndex . '%');
            });
        }
    }

    public function findByPublisher(string $findByPublisher)
    {
        return $this->builder->whereHas('publishers', function ($query) use ($findByPublisher) {
            $query->where('publisher', 'like', '%' . $findByPublisher . '%');
        });
    }

    public function alphabetPublisherIndex(string $alphabetPublisherIndex)
    {
        if ($alphabetPublisherIndex && !$this->request->findByPublisher) {
            return $this->builder->whereHas('publishers', function ($query) use ($alphabetPublisherIndex) {
                $query->where('publisher', 'like', $alphabetPublisherIndex . '%');
            });
        }
    }

    public function findByTitle(string $findByTitle)
    {
        return $this->builder->where('title', 'like', '%' . $findByTitle . '%');
    }

    public function alphabetTitleIndex(string $alphabetTitleIndex)
    {
        if ($alphabetTitleIndex && !$this->request->findByTitle) {
            return $this->builder->where('title', 'like', $alphabetTitleIndex . '%');
        }
    }

    public function sortBy(string $sortBy)
    {
        if ($sortBy === Book::SORT_BY_DATE) {
            return $this->builder->newest();
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
    }

    public function findByCategory(string $findByCategory)
    {
        return $this->builder->whereHas('bookGenres', function ($query) use ($findByCategory) {
            $query->where('id', $findByCategory);
        });
    }

    public function bookType(string $bookType)
    {
        return $this->builder->whereHas('compilations', function ($query) use ($bookType) {
            $query->where('compilationable_type', $bookType);
        });
    }
}
