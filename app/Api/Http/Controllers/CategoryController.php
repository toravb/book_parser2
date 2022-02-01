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
        $genres = BookGenre::orderBy('name')->get();
        return response()->json([
                'status' => 'success',
                'data' => $genres
            ]
        );
    }

    public function showAudioBookGenres()
    {
        $audioBookGenres = AudioGenre::orderBy('name')->get();
        return ApiAnswerService::successfulAnswerWithData($audioBookGenres);
    }

    public function showSelectionType()
    {
        $selectionType = CompilationType::get();
        return response()->json([
                'status' => 'success',
                'data' => $selectionType
            ]
        );
    }
}
