<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AuthorPageRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Compilation;
use App\Models\Review;


class AuthorPageController extends Controller
{
    public function show(AuthorPageRequest $request): \Illuminate\Http\JsonResponse
    {
        $authorWithSeries = Author::with([
            'books' => function ($query) {
                return $query->with([
                    'image' => function ($query) {
                        return $query->where('page_id', null)->select('book_id','link');
                    },
                ])
                    ->whereNull('series_id');
            },
            'audioBooks',
            'series' => function ($query) {
                $query->with(['books' => function ($query) {
                    return $query->with([
                        'image' => function ($query) {
                            return $query->where('page_id', null)->select('book_id','link');
                        },
                    ])->with('rates');
                }]);
            },
            'similarAuthors' => function ($query) use ($request) {
                return $query->with('authors');
            }
        ])
            ->withCount(['authorReviews', 'authorQuotes', 'books'])
            ->find($request->id);

        $authorWithSeries->compilation = Compilation::withCount('books')->get();

        return ApiAnswerService::successfulAnswerWithData($authorWithSeries);
    }

    public function showSeries(AuthorPageRequest $request): \Illuminate\Http\JsonResponse
    {
        $series = Author::with([
            'books' => function ($query) use ($request) {
                return $query->with([
                    'image' => function ($query) {
                        return $query->where('page_id', null)->select('book_id','link');
                    },
                ])->where('series_id', $request->series_id);
            },
            'series' => function ($query) {
                $query->with(['books' => function ($query) {
                    return $query->with([
                        'image' => function ($query) {
                            return $query->where('page_id', null)->select('book_id','link');
                        },
                    ])->with('rates');
                }]);
            },
        ])
            ->withCount(['authorReviews', 'authorQuotes', 'books'])
            ->find($request->id);

        $series->compilation = Compilation::withCount('books')->get();

        return ApiAnswerService::successfulAnswerWithData($series);
    }

    public function showQuotes(Author $author): \Illuminate\Http\JsonResponse
    {
        $quotes = $author->quotes($author->id);
        return ApiAnswerService::successfulAnswerWithData($quotes);
    }

    public function showReviews(Author $author): \Illuminate\Http\JsonResponse
    {
        $reviews = $author->reviews($author->id);
        return ApiAnswerService::successfulAnswerWithData($reviews);
    }
}
