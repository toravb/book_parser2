<?php

namespace App\Api\Filters;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookFilter extends QueryFilter
{
    public function showType(string $viewTypeList): \Illuminate\Database\Eloquent\Builder
    {
        if ($viewTypeList === QueryFilter::SHOW_TYPE_LIST) {
            return $this->builder->withCount(['bookLikes', 'comments'])
                ->with([
                    'year',
                    'publishers'
                ])
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
        if ($sortBy === QueryFilter::SORT_BY_DATE) {
            return $this->builder->orderBy('books.created_at', 'desc');
        }

        if ($sortBy === QueryFilter::SORT_BY_READERS_COUNT) {
            return $this->builder
                ->withCount('readers as readersCount')
                ->orderBy('readersCount', 'desc');
        }

        if ($sortBy === QueryFilter::SORT_BY_RATING_LAST_YEAR) {
            return $this->builder
                ->orderBy('rates_avg', 'desc')
                ->when(count(Book::whereNotNull('created_at')->get()) > 0 , function ($q){
                    $q->whereYear('created_at', '>=', Carbon::now()->subYear()->year);
                });
        }

        if ($sortBy === QueryFilter::SORT_BY_REVIEWS) {
            return $this->builder->withCount('reviews as reviewsCount')->orderBy('reviewsCount', 'desc');
        }

        if ($sortBy === QueryFilter::BESTSELLERS) {
            return $this->builder->orderBy('rates_avg', 'desc');
        }

        if ($sortBy === QueryFilter::SORT_BY_ALPHABET) {
            return $this->builder->orderBy('title', 'asc');
        }

        if($sortBy === QueryFilter::BY_DATE_ADDED_IN_LIST) {
            return $this->builder->orderBy('book_user.created_at', 'desc');
        }

        return $this->builder;
    }

    public function findByCategory(string $findByCategory): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('bookGenres', function ($query) use ($findByCategory) {
            $query->where('id', $findByCategory);
        });
    }

    public function status(string $status)
    {
        if (in_array($status, Book::$availableReadingStatuses) AND $status !== Book::ALL) {
            return $this->builder->where('status', $status);
        }

        return $this->builder;
    }
}
