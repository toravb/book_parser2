<?php

namespace App\Api\Http\Controllers;

use App\Api\Models\UserSettings;
use App\Api\Services\ApiAnswerService;
use App\Api\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $userSettings = UserSettings:: where('user_id', $user->id)
            ->select('likes','commented', 'commentedOthers')->first();

        $user->user_settings = $userSettings;

        return ApiAnswerService::successfulAnswerWithData($user);
    }
}
