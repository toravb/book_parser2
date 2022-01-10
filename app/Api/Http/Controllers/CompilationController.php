<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\StoreCompilationRequest;
use App\Api\Services\ApiAnswerService;
use App\Api\Services\CompilationService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompilationController extends Controller
{
    public function store (StoreCompilationRequest $request, CompilationService $compilation)
    {
        $user = Auth::user();
        $background =$request->file('image')->store('CompilationImages');
        $compilation->storeCompilation($request->title, $background, $request->description, $user->id, $request->compType);

        return ApiAnswerService::successfulAnswerWithData($compilation);

    }
}
