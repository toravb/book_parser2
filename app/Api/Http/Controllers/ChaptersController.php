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

    public function showBookChapters(Book $book): \Illuminate\Http\JsonResponse
    {
        $chapters = $book->chapters;

        return ApiAnswerService::successfulAnswerWithData($chapters);
    }

    public function edit(Chapter $chapter)
    {
        //
    }

    public function update(Request $request, Chapter $chapter)
    {
        //
    }

    public function destroy(Chapter $chapter)
    {
        //
    }
}
