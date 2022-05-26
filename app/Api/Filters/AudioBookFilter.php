<?php

namespace App\Api\Filters;

use App\Models\AudioBook;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AudioBookFilter extends QueryFilter
{
    public function showType(string $viewTypeList): \Illuminate\Database\Eloquent\Builder
    {
        if ($viewTypeList === QueryFilter::SHOW_TYPE_LIST) {
            return $this->builder->withCount(['views', 'rates'])
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
        if ($sortBy === QueryFilter::SORT_BY_DATE) {
            return $this->builder->orderBy('audio_books.created_at', 'desc');
        }

        if ($sortBy === QueryFilter::SORT_BY_LISTENERS) {
            return $this->builder
//                ->withCount('audioBookStatuses as listenerCount')
                ->orderBy('listeners_count', 'desc');
        }

        if ($sortBy === QueryFilter::SORT_BY_RATING_LAST_YEAR) {
            return $this->builder->orderBy('rate_avg', 'desc')
                ->whereYear('created_at', '>=', Carbon::now()->subYear()->year);
        }

        if ($sortBy === QueryFilter::SORT_BY_REVIEWS) {
            return $this->builder->orderBy('reviews_count', 'desc');
//            return $this->builder->withCount('reviews as reviewsCount')->orderBy('reviewsCount', 'desc');
        }

        if ($sortBy === QueryFilter::BESTSELLERS) {
            return $this->builder->orderBy('rate_avg', 'desc');
        }

        if ($sortBy === QueryFilter::SORT_BY_ALPHABET) {
            return $this->builder->orderBy('title', 'asc');
        }

        if($sortBy === QueryFilter::BY_DATE_ADDED_IN_LIST) {
            return $this->builder->orderBy('audio_book_user.created_at', 'desc');
        }

        return $this->builder;
    }

    public function findByCategory(string $findByCategory): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('genre', function ($query) use ($findByCategory) {
            $query->where('id', $findByCategory);
        });
    }

    public function status($status)
    {
        if (in_array($status, AudioBook::$availableListeningStatuses) AND $status !== AudioBook::ALL) {
            return $this->builder->where('status', $status);
        }

        return $this->builder;
    }
}
