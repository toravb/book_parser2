<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioGenre;
use App\Models\CompilationType;
use App\Models\Genre;


class CategoryController extends Controller
{
    public function show()
    {
        return ApiAnswerService::successfulAnswerWithData(Genre::orderBy('name')->get());
    }

    public function showAudioBookGenres(): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData(Genre::orderBy('name')->get());
    }

    public function showSelectionType(): \Illuminate\Http\JsonResponse
    {
        $selectionType = CompilationType::get();
        return response()->json([
                'status' => 'success',
                'data' => $selectionType
            ]
        );
    }

    public function withBooksCount(Genre $genre): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($genre->booksCount());
    }

    public function withAudioBooksCount(Genre $genre): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($genre->audioBooksCount());
    }
}
