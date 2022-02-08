<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\SaveUsersRecommendationRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\UsersRecommendation;
use Illuminate\Http\Request;

class UsersRecommendationsController extends Controller
{
    public function saveUserRecommend(SaveUsersRecommendationRequest $request, UsersRecommendation $recommends)
    {
        return ApiAnswerService::successfulAnswerWithData($recommends->saveRecommend($request));
    }

}
