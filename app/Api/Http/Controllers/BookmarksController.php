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

    public function create(BookmarkRequest $request,  Bookmark $bookmark): \Illuminate\Http\JsonResponse
    {
        $bookmark = $bookmark->addGetBookmark($request);

        return ApiAnswerService::successfulAnswerWithData($bookmark);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Bookmark $bookmark)
    {
        //
    }

    public function edit(Bookmark $bookmark)
    {
        //
    }

    public function update(Request $request, Bookmark $bookmark)
    {
        //
    }

    public function destroy(Bookmark $bookmark): \Illuminate\Http\JsonResponse
    {
        $bookmark->delete();

        return ApiAnswerService::successfulAnswer();
    }
}
