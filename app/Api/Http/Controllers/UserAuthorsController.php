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
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(UserAuthor $userAuthor)
    {
        //
    }

    public function edit(UserAuthor $userAuthor)
    {
        //
    }

    public function update(Request $request, UserAuthor $userAuthor)
    {
        //
    }

    public function destroy(UserAuthor $userAuthor)
    {
        //
    }

    public function addAuthorToFavorites(AddAuthorToFavoritesRequest $request, UserAuthor $userAuthor)
    {

        $user = Auth::user();

        $userAuthor->saveAuthor($user->id, $request->author_id);

        return ApiAnswerService::successfulAnswerWithData($userAuthor);

    }

    public function deleteAuthorFromFavorites(DeleteAuthorFromFavoritesRequest $request, UserAuthor $userAuthor)
    {
        $user = Auth::user();

        $isUsersAuthor = UserAuthor::where('user_id', $user->id)->where('author_id', $request->author_id);

        if($isUsersAuthor)
        {
            $userAuthor->deleteAuthor( $user->id, $request->author_id);
            return ApiAnswerService::successfulAnswerWithData($userAuthor);

        } return ApiAnswerService::errorAnswer("Недостаточно прав для редактирования", Response::HTTP_FORBIDDEN);

    }
}
