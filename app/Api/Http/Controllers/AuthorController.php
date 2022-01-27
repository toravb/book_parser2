<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\GetByLetterAuthorRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Author;

class AuthorController extends Controller
{
    public function showByLetterAuthor(GetByLetterAuthorRequest $request)
    {

        $authors = new Author();
        $authors=$authors->select(['author','id'])
            ->where('author', 'like', $request->letterAuthor . '%')->get();
        return ApiAnswerService::successfulAnswerWithData($authors);
    }
}
