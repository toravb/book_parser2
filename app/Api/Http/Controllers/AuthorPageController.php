<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AuthorPageRequest;
use App\Api\Http\Requests\GetBooksRequest;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\Author;
use App\Models\AuthorToBook;
use App\Models\Book;
use App\Models\Compilation;
use App\Models\Review;
use App\Models\Series;
use App\Models\SimilarAuthors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorPageController extends Controller
{

    public function show(AuthorPageRequest $request): \Illuminate\Http\JsonResponse
    {
        $authorWithSeries = Author::with([
            'books' => function ($query) {
                return $query->with([
                    'image' => function ($q) {
                        return $q->where('page_id', null)->select('book_id','link');
                    },

                ])
                    ->whereNull('series_id');
            },
            'audioBooks',
            'series' => function ($q) {
                $q->with(['books' => function ($q) {
                    return $q->with([
                        'image' => function ($q) {
                            return $q->where('page_id', null)->select('book_id','link');
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
                    'image' => function ($q) {
                        return $q->where('page_id', null)->select('book_id','link');
                    },

                ])->where('series_id', $request->series_id);
            },

            'series' => function ($q) {
                $q->with(['books' => function ($q) {
                    return $q->with([
                        'image' => function ($q) {
                            return $q->where('page_id', null)->select('book_id','link');
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

    public function showQuotes(AuthorPageRequest $request, Author $author): \Illuminate\Http\JsonResponse
    {

        $quotes = $author->quotes($request->id);

        return ApiAnswerService::successfulAnswerWithData($quotes);

    }

    public function showReviews(AuthorPageRequest $request, Author $author): \Illuminate\Http\JsonResponse
    {

        $quotes = $author->reviews($request->id);

        return ApiAnswerService::successfulAnswerWithData($quotes);

    }
}
