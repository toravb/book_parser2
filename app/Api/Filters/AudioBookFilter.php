<?php

namespace App\Api\Filters;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AudioBookFilter extends QueryFilter
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function showType(string $viewTypeList): \Illuminate\Database\Eloquent\Builder
    {
        if ($viewTypeList === Book::SHOW_TYPE_LIST) {
            return $this->builder->withCount('views')
                ->with('year')
                ->addSelect('description');
        }

        return $this->builder;
    }

    public function findByAuthor(string $findByAuthor): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('authors', function ($query) use ($findByAuthor) {
            $query->where('name', 'like', '%' . $findByAuthor . '%');
        });
    }

    public function alphabetAuthorIndex(string $alphabetAuthorIndex): \Illuminate\Database\Eloquent\Builder
    {
        if ($alphabetAuthorIndex && !$this->request->findByAuthor) {
            return $this->builder->whereHas('authors', function ($query) use ($alphabetAuthorIndex) {
                $query->where('name', 'like', $alphabetAuthorIndex . '%');
            });
        }

        return $this->builder;
    }

    public function findBySpeaker(string $findBySpeaker): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('actors', function ($query) use ($findBySpeaker) {
            $query->where('name', 'like', '%' . $findBySpeaker . '%');
        });
    }

    public function alphabetSpeakerIndex(string $alphabetSpeakerIndex): \Illuminate\Database\Eloquent\Builder
    {
        if ($alphabetSpeakerIndex && !$this->request->findBySpeaker) {
            return $this->builder->whereHas('actors', function ($query) use ($alphabetSpeakerIndex) {
                $query->where('name', 'like', $alphabetSpeakerIndex . '%');
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

        if ($sortBy === QueryFilter::SORT_BY_LISTENERS) {
            return $this->builder->whereHas('audioBookStatuses', function ($query) {
                $query->listening();
            })
                ->withCount('audioBookStatuses as listenerCount')
                ->orderBy('listenerCount', 'desc');
        }

        if ($sortBy === Book::SORT_BY_RATING_LAST_YEAR) {
            return $this->builder->orderBy('rates_avg', 'desc')->whereYear('created_at', '>=', Carbon::now()->subYear()->year);
        }

        if ($sortBy === Book::SORT_BY_REVIEWS) {
            return $this->builder->withCount('reviews as reviewsCount')->orderBy('reviewsCount', 'desc');
        }

        if ($sortBy === Book::BESTSELLERS) {
            return $this->builder->orderBy('rates_avg', 'desc');
        }

        return $this->builder;
    }
//
//    public function findByCategory(string $findByCategory): \Illuminate\Database\Eloquent\Builder
//    {
//        return $this->builder->whereHas('bookGenres', function ($query) use ($findByCategory) {
//            $query->where('id', $findByCategory);
//        });
//    }
}
