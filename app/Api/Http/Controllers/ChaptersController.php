<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\ChapterRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChaptersController extends Controller
{
    public function showBookChapters(Book $book): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($book->chapters);
    }
}
