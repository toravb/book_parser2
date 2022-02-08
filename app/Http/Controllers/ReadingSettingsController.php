<?php

namespace App\Http\Controllers;

use App\Api\Http\Requests\ReadingSettingsRequest;
use App\Api\Services\ApiAnswerService;
use App\Models\ReadingSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingSettingsController extends Controller
{
    public function index(ReadingSettings $readingSettings): \Illuminate\Http\JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($readingSettings->where('user_id', Auth::user()->id)->firstOrFail());
    }

    public function create()
    {
        //
    }

    public function store(ReadingSettingsRequest $request, ReadingSettings $readingSettings): \Illuminate\Http\JsonResponse
    {
        $readingSettings->saveReadingSettings(Auth::user()->id, $request);
        return ApiAnswerService::successfulAnswerWithData($readingSettings);
    }

    public function show(ReadingSettings $readingSettings)
    {
        //
    }

    public function edit(ReadingSettings $readingSettings)
    {
        //
    }

    public function update(Request $request, ReadingSettings $readingSettings)
    {
        //
    }

    public function destroy(ReadingSettings $readingSettings)
    {
        //
    }
}
