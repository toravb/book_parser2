<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class ChaptersController extends Controller
{
    public function showBookChapters(Book $book): JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($book->chapters);
    }
}
