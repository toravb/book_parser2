<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\NotificationSettingsRequest;
use App\Api\Models\UserSettings;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class NotificationSettingsController extends Controller
{
    public function create(NotificationSettingsRequest $request, UserSettings $userSettings)
    {

        $user = Auth::user();
        $userSettings->create($user->id, $request->likes, $request->commented, $request->commentedOthers);

        return ApiAnswerService::successfulAnswer();
    }
}
