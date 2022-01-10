<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\StoreRatingValidation;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function store (StoreRatingValidation $request, Rate $rating)
    {
        $user = Auth::user();

        $rating->store($user->id, $request->book_id, $request->rating);
        
       return ApiAnswerService::successfulAnswerWithData($rating);
    }
}
