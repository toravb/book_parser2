<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\GetByLetterRequest;
use App\Api\Http\Requests\IsAuthorExistsRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorsFilteringByLetterRequest;
use App\Models\Author;

class AuthorController extends Controller
{
    public function showByLetter(GetByLetterRequest $request, Author $authors): \Illuminate\Http\JsonResponse
    {
        $authors = $authors->select(['id', 'author'])
            ->where('author', 'like', $request->letter . '%')->get();

        return ApiAnswerService::successfulAnswerWithData($authors);
    }

    public function showOtherBooks(IsAuthorExistsRequest $id, Author $author)
    {
        return ApiAnswerService::successfulAnswerWithData($author->showOtherBooks($id->id));
    }

    public function showOtherAudioBooks(IsAuthorExistsRequest $id, Author $author)
    {
        return ApiAnswerService::successfulAnswerWithData($author->showOtherAudioBooks($id->id));
    }

    public function filterByLetter(AuthorsFilteringByLetterRequest $request, Author $author)
    {
        return ApiAnswerService::successfulAnswerWithData($author->letterFiltering($request->letter));
    }
}
