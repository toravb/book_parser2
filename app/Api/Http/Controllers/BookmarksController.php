<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Controllers\LoginController;
use App\AuthApi\Models\IdSocialNetwork;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookmarkRequest;
use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BookmarksController extends Controller
{
    public function index()
    {
        //
    }

    public function create(BookmarkRequest $request, Bookmark $bookmark): \Illuminate\Http\JsonResponse
    {
        $bookmark = $bookmark->addGetBookmark($request);
        $bookmark->page = $bookmark->page()->select('id', 'book_id', 'page_number')->get();

        return ApiAnswerService::successfulAnswerWithData($bookmark);
    }

    public function destroy(Bookmark $bookmark): \Illuminate\Http\JsonResponse
    {
        $bookmark->delete();

        return ApiAnswerService::successfulAnswer();
    }
}
