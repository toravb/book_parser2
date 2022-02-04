<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AuthorPageRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Compilation;

class AuthorPageController extends Controller
{
    public function show(AuthorPageRequest $request): \Illuminate\Http\JsonResponse
    {
        $authorWithSeries = Author::with([
            'books' => function ($query) {
                $query->whereNull('series_id');
            },
            'audioBooks',
            'series' => function ($q) {
                $q->with(['books' => function ($q) {
                    $q->with('rates');
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
                $query->where('series_id', $request->series_id);
            },

            'series' => function ($q) {
                $q->with(['books' => function ($q) {
                    $q->with('rates');
                }]);
            },

        ])
            ->withCount(['authorReviews', 'authorQuotes', 'books'])
            ->find($request->id);

        $series->compilation = Compilation::withCount('books')->get();


        return ApiAnswerService::successfulAnswerWithData($series);
    }
}
