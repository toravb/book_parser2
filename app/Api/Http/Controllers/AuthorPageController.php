<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AuthorPageRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Compilation;
use App\Models\Review;
use Illuminate\Http\JsonResponse;


class AuthorPageController extends Controller
{
    public function show(Author $author): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($author->authorPage());
    }

    public function showSeries(AuthorPageRequest $request): JsonResponse
    {
        $series = Author::query()
            ->with([
                'books' => function ($query) use ($request) {
                    return $query
                        ->with(['image'])
                        ->where('series_id', $request->series_id);
                },
                'series',
                'series.books.image',
                'series.books.rates',
            ])
            ->withCount(['authorReviews', 'authorQuotes', 'books'])
            ->find($request->id);

        $series->compilation = Compilation::withCount('books')->get();

        return ApiAnswerService::successfulAnswerWithData($series);
    }

    public function showQuotes($authorId, $bookId): JsonResponse
    {
        $author = Author::withQuotesCount($authorId);
        $books = Book::where('id', $bookId)
            ->latestBookQuoteWithUser($authorId);

        return ApiAnswerService::successfulAnswerWithData([$author, $books]);
    }

    public function showReviews($authorId, $bookId): JsonResponse
    {
        $author = Author::reviewAuthorCount($authorId);
        $books = Book::where('id', $bookId)
            ->latestBookReviewWithUser($authorId);

        return ApiAnswerService::successfulAnswerWithData([$author, $books]);
    }
}
