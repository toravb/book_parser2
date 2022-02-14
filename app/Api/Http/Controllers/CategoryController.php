<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioGenre;
use App\Models\BookGenre;
use App\Models\CompilationType;


class CategoryController extends Controller
{
    public function show()
    {
        return ApiAnswerService::successfulAnswerWithData(BookGenre::orderBy('name')->get());
    }

    public function showAudioBookGenres(): \Illuminate\Http\JsonResponse
    {
        $audioBookGenres = AudioGenre::orderBy('name')->get();
        return ApiAnswerService::successfulAnswerWithData($audioBookGenres);
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

    public function withBooksCount(BookGenre $count): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($count->booksCount());
    }

    public function withAudioBooksCount(AudioGenre $count): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($count->audioBooksCount());
    }

}
