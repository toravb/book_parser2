<?php

namespace App\Api\Http\Controllers;

use App\Api\Filters\BookFilter;
use App\Api\Http\Requests\MainPageBookFilterRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\Book;
use App\Models\BookReview;
use App\Models\Compilation;
use App\Models\Genre;

class MainPageController extends Controller
{

    const MAIN_PAGE_NEW_BOOKS_COMPILATION = 1;
    const MAIN_PER_PAGE = 16;
    const PERIOD_FOR_HOT_DAILY_UPDATES = 10;


    public function home(
        MainPageBookFilterRequest $request,
        Compilation               $compilation,
        AudioBook                 $audioBook,
        BookReview                $review,
        CategoryController        $categoryController,
        Book                      $book,
        BookFilter                $bookFilter
    ): \Illuminate\Http\JsonResponse
    {
        $genres = Genre::orderBy('name')->limit(13)->get();

        $newBooksCompilation = $compilation->newBooksMainPage(self::MAIN_PAGE_NEW_BOOKS_COMPILATION);

        $bookDailyHot = $book->hotDailyUpdates();

        $compilations = $compilation->withSumAudioAndBooksCount();

        $audioBooksList = $audioBook->mainPagePaginateList();

        $mainPageReview = $review->latestReviewBookUser();

        $mainPageBooksFilter = $book->getBooksForMainPageFilter()->filter($bookFilter)->paginate(self::MAIN_PER_PAGE);

        return ApiAnswerService::successfulAnswerWithData([
            'genres' => $genres,
            'newBooksCompilations' => $newBooksCompilation,
            'dailyHotUpdates' => $bookDailyHot,
            'mainPageBookFilter' => $mainPageBooksFilter,
            'compilations' => $compilations,
            'audioBooksList' => $audioBooksList,
            'reviews' => $mainPageReview
        ]);
    }
}

