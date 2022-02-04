<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\GetByLetterRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Author;

class AuthorController extends Controller
{
    public function showByLetter(GetByLetterRequest $request, Author $authors): \Illuminate\Http\JsonResponse
    {
        $authors = $authors->select(['id', 'author'])
            ->where('author', 'like', $request->letter . '%')->get();

        return ApiAnswerService::successfulAnswerWithData($authors);
    }

    public function showOtherBooks($id, Author $author)
    {
        return ApiAnswerService::successfulAnswerWithData($author->showOtherBooks($id));
    }

    public function showOtherAudioBooks($id, Author $author)
    {
        return ApiAnswerService::successfulAnswerWithData($author->showOtherAudioBooks($id));
    }
}
