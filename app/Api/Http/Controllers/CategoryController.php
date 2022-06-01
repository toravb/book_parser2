<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\CompilationType;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;


class CategoryController extends Controller
{
    public function show()
    {
        return ApiAnswerService::successfulAnswerWithData(Genre::orderBy('name')->get());
    }

    public function showAudioBookGenres(): JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData(Genre::orderBy('name')->get());
    }

    public function showSelectionType(): JsonResponse
    {
        $selectionType = CompilationType::get();
        return response()->json([
                'status' => 'success',
                'data' => $selectionType
            ]
        );
    }

    public function withBooksCount(Genre $genre): JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($genre->relatedWithBook());
    }

    public function withAudioBooksCount(Genre $genre): JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($genre->relatedWithAudioBook());
    }

}
