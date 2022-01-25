<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AddAuthorToFavoritesRequest;
use App\Api\Http\Requests\DeleteAuthorFromFavoritesRequest;
use App\Api\Http\Requests\DeleteBookFromUsersListRequst;
use App\Api\Http\Requests\SaveBookRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\BookUser;
use App\Models\UserAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAuthorsController extends Controller
{
     public function store(AddAuthorToFavoritesRequest $request, UserAuthor $userAuthor)
    {

        $userAuthor->saveAuthor(Auth::user()->id, $request->author_id);
        return ApiAnswerService::successfulAnswerWithData($userAuthor);

    }

    public function destroy(DeleteAuthorFromFavoritesRequest $request, UserAuthor $userAuthor)
    {
        $userAuthor->deleteAuthor(Auth::user()->id, $request->author_id);
        return ApiAnswerService::successfulAnswerWithData($userAuthor);

    }

}
