<?php

namespace App\Api\Http\Controllers;

use App\api\Http\Requests\NotificationSettingsRequest;
use App\Api\Models\UserSettings;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NotificationSettingsController extends Controller
{
    public function create(NotificationSettingsRequest $request, UserSettings $userSettings)
    {

        $user = Auth::user();
        $userSettings->create($user, $request);


        return ApiAnswerService::successfulAnswer();
    }
}
