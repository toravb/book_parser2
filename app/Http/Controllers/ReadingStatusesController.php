<?php

namespace App\Http\Controllers;

use App\Api\Http\Requests\ReadingStatusShowRequest;
use App\Api\Http\Requests\ReadingStatusStoreRequest;
use App\Api\Services\ApiAnswerService;
use App\Models\Book;
use App\Models\ReadingStatus;

class ReadingStatusesController extends Controller
{
    public function storeReadingProgress(ReadingStatusStoreRequest $request, ReadingStatus $status, Book $book)
    {
        $totalPages = $book->select('count_pages')->find($request->id);
        $readingProgress = round(($request->page_number / $totalPages->count_pages) * 100);

        return ApiAnswerService::successfulAnswerWithData(
            $status->storeCurrentReadingStatus($request->id, $request->page_number, $readingProgress)
        );
    }

    public function showSavedReadingProgress(ReadingStatusShowRequest $request, ReadingStatus $readingStatus)
    {
        return ApiAnswerService::successfulAnswerWithData($readingStatus->show($request->id));
    }

    public function booksWithReadingProgress(Book $book)
    {
        return ApiAnswerService::successfulAnswerWithData($book->forReadingProgressInUserList()->get());
    }
}
