<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\Book;
use App\Models\BookReview;
use App\Models\Compilation;

class MainPageController extends Controller
{
    const COMPILATION_PAGINATION = 3;
    const REVIEWS_PAGINATION = 3;
    const GENRES_PAGINATION = 13;

    public function home(Compilation $compilation, AudioBook $audioBook, BookReview $review, CategoryController $categoryController, Book $bookDaily): \Illuminate\Http\JsonResponse
    {
        $genres = $categoryController->show();

        $bookDailyHot = $bookDaily->hotDailyUpdates();

        $compilations = $compilation->withSumAudioAndBooksCount();

        $audioBooksList = $audioBook->mainPagePaginateList();

        $mainPageReview = $review->latestReviewBookUser();

//        return ApiAnswerService::successfulAnswerWithData($bookDailyHot);
        return ApiAnswerService::successfulAnswerWithData([
            'genres' => $genres,
            'dailyHotUpdates' => $bookDailyHot,
            'compilations' => $compilations,
            'audioBooksList' => $audioBooksList,
            'reviews' => $mainPageReview
        ]);
    }
}

