<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\GetIdRequest;
use App\Api\Services\ApiAnswerService;
use App\Api\Services\CompilationService;
use App\Http\Controllers\Controller;

class CompilationLoadingController extends Controller
{
    public function compilationLoading(GetIdRequest $request, CompilationService $compilationService){
        $id = $request->id;
        $books = $compilationService->showCompilationDetails($id);
        return ApiAnswerService::successfulAnswerWithData($books);
    }
}
