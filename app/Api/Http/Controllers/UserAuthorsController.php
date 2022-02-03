<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AddAuthorToFavoritesRequest;
use App\Api\Http\Requests\DeleteAuthorFromFavoritesRequest;
use App\Api\Http\Requests\GetUserAuthorsRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;

use App\Models\UserAuthor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserAuthorsController extends Controller
{
    public function list(GetUserAuthorsRequest $request)
    {
        $authors = \auth()->user()->authors()
            ->where(function ($query) use ($request ){
                $query->when($request->letter!==null, function ($query) use ($request){
                    $query->where('author', 'like', '%' . $request->letter . '%');
                });
                })->get();

        return ApiAnswerService::successfulAnswerWithData($authors);
    }

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
