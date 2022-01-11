<?php

namespace App\Api\Http\Controllers;

use App\api\Http\Requests\NotificationSettingsRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NotificationSettingsController extends Controller
{
    public function create(NotificationSettingsRequest $request)
    {
        $user = Auth::user();
            DB::table('user_settings')->
                where('user_id', $user->id)
                ->update([
                    'likes' => $request->like,
                    'commented' => $request->commented,
                    'commentedOthers' => $request->commentedOthers]);

            DB::commit();

        return ApiAnswerService::successfulAnswer();
    }
}
