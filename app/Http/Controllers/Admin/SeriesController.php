<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Series $series, Request $request)
    {
        $series = $series->paginate(25);

        if ($request->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($series);
        }

        return view('admin.series.index', compact('series'));
    }
}
