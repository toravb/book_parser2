<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AddAuthorToFavoritesRequest;
use App\Api\Http\Requests\DeleteAuthorFromFavoritesRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\UserAuthor;
use Illuminate\Support\Facades\Auth;

class UserAuthorsController extends Controller
{
    public function store(AddAuthorToFavoritesRequest $request, UserAuthor $userAuthor): \Illuminate\Http\JsonResponse
    {
        $userAuthor->saveAuthor(Auth::id(), $request->author_id);
        return ApiAnswerService::successfulAnswer();
    }

    public function destroy(DeleteAuthorFromFavoritesRequest $request, UserAuthor $userAuthor): \Illuminate\Http\JsonResponse
    {
        $userAuthor->deleteAuthor(Auth::id(), $request->author_id);
        return ApiAnswerService::successfulAnswer();
    }
}
