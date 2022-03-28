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
    const FAVORITE_AUTHORS_QUANTITY = 12;

    public function list(GetUserAuthorsRequest $request)
    {
        $authors = \auth()->user()->authors()
            ->when($request->letter !== null, function ($query) use ($request) {
                $query->where('author', 'like', $request->letter . '%');
            })
            ->select('id', 'author', 'avatar')
            ->withCount('books', 'audioBooks')
//            ->get();
            ->paginate(self::FAVORITE_AUTHORS_QUANTITY);

        $authors->map(function ($query) {
            $query->total_books_count = $query->books_count + $query->audio_books_count;
        });

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
