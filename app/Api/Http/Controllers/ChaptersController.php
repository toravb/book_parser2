<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\ChapterRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
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

    public function showBookContents(Chapter $chapter, ChapterRequest $request)
    {
        $chapter=$chapter->select('title')
                ->where('book_id', $request->id)->get();

        return ApiAnswerService::successfulAnswerWithData($chapter);
    }

    public function showChapter(Chapter $chapter, ChapterRequest $request)
    {
        $chapter=$chapter->select('title')
            ->orderBy('page_id', 'desc')
            ->where('page_id', '<=', $request->page_id)
            ->where('book_id', $request->book_id)
            ->first();

        return ApiAnswerService::successfulAnswerWithData($chapter);
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
