<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;

class BookmarksController extends Controller
{
    public function create(BookmarkRequest $request, Bookmark $bookmark): JsonResponse
    {
        $bookmark = $bookmark->addGetBookmark($request);
        $bookmark->page = $bookmark
            ->page()
            ->select(['id', 'book_id', 'page_number'])
            ->get();

        return ApiAnswerService::successfulAnswerWithData($bookmark);
    }

    public function destroy(Bookmark $bookmark): JsonResponse
    {
        $bookmark->delete();

        return ApiAnswerService::successfulAnswer();
    }
}
