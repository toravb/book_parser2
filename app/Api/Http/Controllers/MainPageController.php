<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\BookReview;
use App\Models\Compilation;

class MainPageController extends Controller
{
    const COMPILATION_PAGINATION = 3;
    const REVIEWS_PAGINATION = 3;

    public function home(Compilation $compilation, AudioBook $audioBook, BookReview $review): \Illuminate\Http\JsonResponse
    {
        $compilations = $compilation->withSumAudioAndBooksCount();

        $audioBooksList = $audioBook->mainPagePaginateList();

        $mainPageReview = $review->latestReviewBookUser();

        return ApiAnswerService::successfulAnswerWithData([
            'compilations' => $compilations,
            'audioBooksList' => $audioBooksList,
            'reviews' => $mainPageReview
        ]);
    }
}

