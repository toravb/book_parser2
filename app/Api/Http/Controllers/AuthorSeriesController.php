<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AuthorSeriesRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Series;

class AuthorSeriesController extends Controller
{
    public function showSeries($id, Series $series)
    {
        return ApiAnswerService::successfulAnswerWithData($series->getSeries($id)->firstOrFail());
    }
}
