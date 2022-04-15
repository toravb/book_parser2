<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AuthorPageRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Compilation;
use App\Models\Review;


class AuthorPageController extends Controller
{
    public function show(Author $author): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($author->authorPage());
    }

    public function showSeries(AuthorPageRequest $request): \Illuminate\Http\JsonResponse
    {
        $series = Author::with([
            'books' => function ($query) use ($request) {
                return $query->with([
                    'image' => function ($query) {
                        return $query->where('page_id', null)->select('book_id', 'link');
                    },
                ])->where('series_id', $request->series_id);
            },
            'series' => function ($query) {
                $query->with(['books' => function ($query) {
                    return $query->with([
                        'image' => function ($query) {
                            return $query->where('page_id', null)->select('book_id', 'link');
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

    public function showQuotes(Author $author, Book $book): \Illuminate\Http\JsonResponse
    {
        $author = $author->quotes($author->id);
        $book = $book->latestBookQuoteWithUser($author->id);
        return ApiAnswerService::successfulAnswerWithData([$author, $book]);
    }

    public function showReviews(Author $author, Book $book): \Illuminate\Http\JsonResponse
    {
        $author = $author->reviewAuthorCount($author->id);
        $book = $book->latestBookReviewWithUser($author->id);
        return ApiAnswerService::successfulAnswerWithData([$author, $book]);
    }
}
