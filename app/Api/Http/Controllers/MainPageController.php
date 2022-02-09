<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Compilation;

class MainPageController extends Controller
{
    const COMPILATION_PAGINATION = 3;

    public function home(Compilation $compilation): \Illuminate\Http\JsonResponse
    {
        $compilations = $compilation->withSumAudioAndBooksCount();

        return ApiAnswerService::successfulAnswerWithData($compilations);
    }
}
